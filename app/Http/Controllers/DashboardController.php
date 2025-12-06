<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Provider;
use App\Services\AppointmentSlotService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $slotService;

    public function __construct(AppointmentSlotService $slotService)
    {
        // Apply auth middleware only to dashboard routes, not booking routes
        $this->middleware('auth')->except(['bookingPage', 'availableSlots', 'storeAppointment', 'thankYou']);
        $this->slotService = $slotService;
    }
    
    public function index()
    {
        $user = auth()->user();
        
        // Redirect to role-specific dashboard
        if ($user->isAdmin()) {
            return redirect()->route('filament.admin.pages.dashboard');
        } elseif ($user->isProvider()) {
            return redirect()->route('provider.dashboard');
        } elseif ($user->isCustomer()) {
            return redirect()->route('customer.dashboard');
        }
        
        // Fallback if no role matched
        abort(403, 'Your account does not have access to any dashboard.');
    }

    public function bookingPage(Provider $provider = null)
    {
        // Load all active providers
        $providers = Provider::with(['services', 'user'])
            ->where('is_active', true)
            ->get();
        
        // If specific provider requested, load it
        if ($provider) {
            $provider->load(['services', 'user']);
        } else {
            // Select first provider as default
            $provider = $providers->first();
        }
        
        return view('pages.appointments.book', compact('provider', 'providers'));
    }

    public function availableSlots(Request $request, Provider $provider)
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'service_ids' => 'required|string',
        ]);

        $serviceIds = explode(',', $validated['service_ids']);
        $services = \App\Models\Service::whereIn('id', $serviceIds)->get();
        
        if ($services->isEmpty()) {
            return response()->json([
                'success' => false,
                'errors' => ['service_ids' => 'Invalid service IDs']
            ], 422);
        }

        $slots = $this->slotService->getAvailableSlotsForMultipleServices(
            $provider,
            $services,
            $validated['date']
        );

        return response()->json([
            'success' => true,
            'data' => [
                'date' => $validated['date'],
                'slots' => $slots->values()->toArray(),
                'total_duration' => $services->sum('duration'),
            ],
        ]);
    }

    public function storeAppointment(Request $request)
    {
        // Base validation rules
        $rules = [
            'provider_id' => 'required|exists:providers,id',
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
        ];

        // Add guest user validation rules if not authenticated
        if (!auth()->check()) {
            $rules['guest_name'] = 'required|string|max:255';
            $rules['guest_email'] = 'required|email|max:255';
            $rules['guest_phone'] = 'required|string|max:20';
        }

        $validated = $request->validate($rules);

        try {
            // Handle guest user creation/login
            $customerId = null;
            $password = null;
            $isNewGuestUser = false;

            if (auth()->check()) {
                // Authenticated user
                $customerId = auth()->user()->id;
            } else {
                // Guest user - create or find account
                $user = \App\Models\User::where('email', $validated['guest_email'])->first();
                
                if (!$user) {
                    // Create new user account
                    $password = \Illuminate\Support\Str::random(10);
                    $customerRole = \App\Models\Role::where('name', 'customer')->first();
                    
                    $user = \App\Models\User::create([
                        'name' => $validated['guest_name'],
                        'email' => $validated['guest_email'],
                        'phone' => $validated['guest_phone'],
                        'password' => \Illuminate\Support\Facades\Hash::make($password),
                        'role_id' => $customerRole ? $customerRole->id : 3,
                    ]);
                    
                    $isNewGuestUser = true;
                } else {
                    // Update existing user info
                    $user->update([
                        'name' => $validated['guest_name'],
                        'phone' => $validated['guest_phone'],
                    ]);
                }
                
                $customerId = $user->id;
            }

            $provider = Provider::findOrFail($validated['provider_id']);
            $services = \App\Models\Service::whereIn('id', $validated['service_ids'])->get();
            
            $appointment = $this->slotService->bookAppointmentWithMultipleServices(
                $provider,
                $customerId,
                $services,
                $validated['appointment_date'],
                $validated['start_time'],
                $request->input('notes')
            );

            // Load relationships for thank you page
            $appointment->load(['provider.user', 'services', 'customer']);

            // Create notifications
            $customerName = $appointment->customer->name;
            $providerName = $appointment->provider->name;
            $date = \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y');
            $time = \Carbon\Carbon::parse($appointment->start_time)->format('g:i A');
            
            // Notify Customer
            makeNotification(
                $customerId,
                'Booking Confirmed',
                "Your appointment with {$providerName} on {$date} at {$time} has been submitted and is pending approval.",
                route('customer.booking.details', $appointment->id),
                'booking'
            );
            
            // Notify Provider
            makeNotification(
                $appointment->provider->user_id,
                'New Booking Request',
                "{$customerName} has requested an appointment on {$date} at {$time}. Please review and confirm.",
                route('provider.booking.details', $appointment->id),
                'booking'
            );

            // Send appointment booked email to customer
            \Illuminate\Support\Facades\Mail::to($appointment->customer->email)
                ->send(new \App\Mail\AppointmentBooked(
                    $appointment->customer,
                    'customer',
                    $appointment
                ));

            // Send appointment booked email to provider
            \Illuminate\Support\Facades\Mail::to($appointment->provider->user->email)
                ->send(new \App\Mail\AppointmentBooked(
                    $appointment->provider->user,
                    'provider',
                    $appointment
                ));

            // Send credentials email for new guest users
            if ($isNewGuestUser && $password) {
                try {
                    \Illuminate\Support\Facades\Log::info('Sending guest credentials email', [
                        'email' => $appointment->customer->email,
                        'user_id' => $appointment->customer->id,
                        'appointment_id' => $appointment->id
                    ]);

                    \Illuminate\Support\Facades\Mail::to($appointment->customer->email)
                        ->send(new \App\Mail\GuestAccountCreated(
                            $appointment->customer,
                            $password,
                            $appointment
                        ));

                    \Illuminate\Support\Facades\Log::info('Guest credentials email sent successfully');
                } catch (\Exception $mailException) {
                    \Illuminate\Support\Facades\Log::error('Failed to send guest credentials email: ' . $mailException->getMessage());
                }
            }

            return redirect()
                ->route('appointments.thank-you')
                ->with('appointment', $appointment)
                ->with('isNewGuestUser', $isNewGuestUser)
                ->with('guestPassword', $password);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function thankYou()
    {
        // Check if appointment data exists in session
        if (!session()->has('appointment')) {
            // Redirect to home for guest users, dashboard for authenticated users
            return auth()->check() ? redirect()->route('dashboard') : redirect()->route('home');
        }

        return view('pages.appointments.thank-you');
    }
}
