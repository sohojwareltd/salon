<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Services\WalletService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function __construct(
        protected WalletService $walletService
    ) {}

    public function index()
    {
      
        $provider = auth()->user()->provider;

        if (!$provider) {
            abort(403, 'You are not associated with any provider profile.');
        }

        // Monthly statistics
        $monthCompletedCount = $provider->appointments()
            ->where('status', 'completed')
            ->whereMonth('appointment_date', Carbon::now()->month)
            ->whereYear('appointment_date', Carbon::now()->year)
            ->count();

        $lastMonthCompletedCount = $provider->appointments()
            ->where('status', 'completed')
            ->whereMonth('appointment_date', Carbon::now()->subMonth()->month)
            ->whereYear('appointment_date', Carbon::now()->subMonth()->year)
            ->count();

        $growthRate = $lastMonthCompletedCount > 0 
            ? (($monthCompletedCount - $lastMonthCompletedCount) / $lastMonthCompletedCount) * 100 
            : 0;

        // Monthly earnings amount
        $currentMonthEarnings = $provider->walletEntries()
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_provider_amount');

        // Statistics
        $stats = [
            'today_appointments' => $provider->appointments()
                ->whereDate('appointment_date', today())
                ->count(),
            'pending_appointments' => $provider->appointments()
                ->where('status', 'pending')
                ->count(),
            'confirmed_appointments' => $provider->appointments()
                ->where('status', 'confirmed')
                ->count(),
            'completed_today' => $provider->appointments()
                ->whereDate('appointment_date', today())
                ->where('status', 'completed')
                ->count(),
            'wallet_balance' => $provider->wallet_balance,
            'total_reviews' => $provider->total_reviews ?? 0,
            'average_rating' => $provider->average_rating ?? 0,
            'month_completed' => $monthCompletedCount,
            'current_month_earnings' => $currentMonthEarnings,
            'earnings_target' => 50000, // Can be stored in provider settings
            'booking_target' => 100, // Can be stored in provider settings
            'growth_rate' => $growthRate,
        ];

        // Monthly earnings summary
        $monthlyEarnings = $this->walletService->getProviderEarningsSummary($provider);

        // Today's appointments
        $todayAppointments = $provider->appointments()
            ->with(['user', 'service'])
            ->whereDate('appointment_date', today())
            ->orderBy('start_time')
            ->get();

        // Upcoming appointments
        $upcomingAppointments = $provider->appointments()
            ->with(['user', 'service'])
            ->where('appointment_date', '>', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        // Weekly earnings chart
        $weeklyEarnings = $this->getWeeklyEarnings($provider);

        return view('provider.dashboard', compact(
            'provider',
            'stats',
            'monthlyEarnings',
            'todayAppointments',
            'upcomingAppointments',
            'weeklyEarnings'
        ));
    }

    public function bookings(Request $request)
    {
        $provider = auth()->user()->provider;

        $query = $provider->appointments()
            ->with(['user', 'service', 'payment']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }

        $appointments = $query->latest('appointment_date')->paginate(20);

        $stats = [
            'pending_appointments' => $provider->appointments()->where('status', 'pending')->count(),
        ];

        return view('provider.bookings.index', compact('provider', 'stats', 'appointments'));
    }

    public function bookingDetails(Appointment $appointment)
    {
        $provider = auth()->user()->provider;
        
        // Verify ownership
        if ($appointment->provider_id !== $provider->id) {
            abort(403, 'Unauthorized action.');
        }

        // Load relationships
        $appointment->load(['customer', 'services', 'payment', 'review', 'walletEntry']);

        // Calculate provider earnings if wallet entry exists
        $earnings = null;
        if ($appointment->walletEntry) {
            $earnings = [
                'service_amount' => $appointment->walletEntry->service_amount,
                'commission' => $appointment->walletEntry->provider_amount,
                'tips' => $appointment->walletEntry->tips_amount ?? 0,
                'total' => $appointment->walletEntry->total_provider_amount,
            ];
        }

        return view('provider.booking-details', compact('appointment', 'provider', 'earnings'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $provider = auth()->user()->provider;

        // Verify ownership
        if ($appointment->provider_id !== $provider->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:confirmed,completed,cancelled',
        ]);

        $oldStatus = $appointment->status;

        $appointment->update([
            'status' => $request->status,
            'completed_at' => $request->status === 'completed' ? now() : null,
        ]);

        // Create wallet entry when appointment is completed
        if ($request->status === 'completed' && $oldStatus !== 'completed') {
            // Check if wallet entry doesn't already exist
            if (!$appointment->walletEntry) {
                $this->walletService->createWalletEntryFromAppointment($appointment);
            }
        }

        // Send notifications based on status change
        $appointment->load(['customer', 'provider.user']);
        $providerName = $appointment->provider->name;
        $date = \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y');
        $time = \Carbon\Carbon::parse($appointment->start_time)->format('g:i A');
        
        if ($request->status === 'confirmed' && $oldStatus !== 'confirmed') {
            // Notify Customer - Appointment Confirmed
            makeNotification(
                $appointment->customer_id,
                'Appointment Confirmed',
                "Your appointment with {$providerName} on {$date} at {$time} has been confirmed!",
                route('customer.booking.details', $appointment->id),
                'approval'
            );
        } elseif ($request->status === 'completed' && $oldStatus !== 'completed') {
            // Notify Customer - Request Review
            makeNotification(
                $appointment->customer_id,
                'Appointment Completed',
                "Your appointment with {$providerName} is complete! We'd love to hear your feedback.",
                route('customer.review', $appointment->id),
                'review_request'
            );
            
            // Notify Provider
            makeNotification(
                $appointment->provider->user_id,
                'Appointment Completed',
                "Appointment on {$date} at {$time} marked as completed.",
                route('provider.booking.details', $appointment->id),
                'complete'
            );
        }

        return back()->with('success', 'Appointment status updated successfully.');
    }

    public function wallet()
    {
        $provider = auth()->user()->provider;

        $summary = $this->walletService->getProviderEarningsSummary($provider);

        $walletEntries = $provider->walletEntries()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Monthly trend
        $monthlyTrend = $provider->walletEntries()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_provider_amount) as total')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get()
            ->reverse();

        return view('provider.wallet.index', compact('summary', 'walletEntries', 'monthlyTrend'));
    }

    public function reviews()
    {
        $provider = auth()->user()->provider;

        $reviews = $provider->reviews()
            ->with(['user', 'appointment.service'])
            ->latest()
            ->paginate(15);

        $stats = [
            'pending_appointments' => $provider->appointments()->where('status', 'pending')->count(),
        ];

        return view('provider.reviews.index', compact('provider', 'stats', 'reviews'));
    }

    public function profile()
    {
        $provider = auth()->user()->provider;
        $stats = [
            'pending_appointments' => $provider->appointments()->where('status', 'pending')->count(),
        ];

        return view('provider.profile', compact('provider', 'stats'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Check current password if provided
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
        }

        // Update user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'specialization' => $request->specialization,
            'bio' => $request->bio,
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Profile updated successfully!');
    }
    
    public function updateProfileInfo(Request $request)
    {
        $provider = auth()->user()->provider;
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:providers,email,' . $provider->id . '|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'expertise' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Check current password if provided
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
        }

        // Handle photo upload
        $photoPath = $provider->photo;
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($provider->photo && \Storage::disk('public')->exists($provider->photo)) {
                \Storage::disk('public')->delete($provider->photo);
            }
            $photoPath = $request->file('photo')->store('providers/photos', 'public');
        }

        // Update provider
        $provider->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'expertise' => $request->expertise,
            'bio' => $request->bio,
            'photo' => $photoPath,
        ]);

        // Update user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Profile information updated successfully!');
    }

    public function settings()
    {
        $provider = auth()->user()->provider;
        $stats = [
            'pending_appointments' => $provider->appointments()->where('status', 'pending')->count(),
        ];

        return view('provider.settings', compact('provider', 'stats'));
    }

    public function updateSettings(Request $request)
    {
        $provider = auth()->user()->provider;

        $request->validate([
            'schedule' => 'required|array|min:7',
            'schedule.*.weekday' => 'required|integer|between:0,6',
            'schedule.*.enabled' => 'nullable',
            'schedule.*.start_time' => 'nullable|date_format:H:i',
            'schedule.*.end_time' => 'nullable|date_format:H:i',
            'has_break' => 'nullable',
            'break_start' => 'nullable|date_format:H:i',
            'break_end' => 'nullable|date_format:H:i',
            'buffer_time' => 'required|integer|min:0|max:60',
        ]);

        // Update break times and buffer time
        $provider->update([
            'break_start' => $request->has_break ? $request->break_start : null,
            'break_end' => $request->has_break ? $request->break_end : null,
            'buffer_time' => $request->buffer_time,
        ]);

        // Update schedules for each day
        foreach ($request->schedule as $scheduleData) {
            $weekday = $scheduleData['weekday'];
            $enabled = isset($scheduleData['enabled']) && $scheduleData['enabled'] == '1';
            
            $provider->schedules()->updateOrCreate(
                ['weekday' => $weekday],
                [
                    'start_time' => ($enabled && isset($scheduleData['start_time'])) ? $scheduleData['start_time'] : '09:00',
                    'end_time' => ($enabled && isset($scheduleData['end_time'])) ? $scheduleData['end_time'] : '18:00',
                    'is_off' => !$enabled,
                ]
            );
        }

        return back()->with('success', 'Schedule settings updated successfully!');
    }
    
    public function updateSocial(Request $request)
    {
        $provider = auth()->user()->provider;

        $request->validate([
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'twitter' => 'nullable|url',
            'youtube' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'website' => 'nullable|url',
        ]);

        $provider->update($request->only(['facebook', 'instagram', 'twitter', 'youtube', 'linkedin', 'website']));

        return back()->with('success', 'Social media links updated successfully!');
    }

    public function updateNotifications(Request $request)
    {
        $user = Auth::user();

        $notifications = $request->input('notifications', []);

        $user->update([
            'notifications' => $notifications,
        ]);

        return back()->with('success', 'Notification preferences updated successfully!');
    }

    protected function getWeeklyEarnings($provider)
    {
        $days = [];
        $earnings = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = $date->format('D');

            $dayEarnings = $provider->walletEntries()
                ->whereDate('created_at', $date)
                ->sum('total_provider_amount');

            $earnings[] = round($dayEarnings, 2);
        }

        return [
            'labels' => $days,
            'data' => $earnings,
        ];
    }

    // ==================== Services CRUD Methods ====================

    public function servicesIndex()
    {
        $provider = auth()->user()->provider;
        $services = $provider->services()->orderBy('created_at', 'desc')->get();
        
        return view('provider.services.index', compact('services'));
    }

    public function servicesCreate()
    {
        return view('provider.services.create');
    }

    public function servicesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'duration' => 'required|integer|min:5|max:480',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $provider = auth()->user()->provider;
        
        $service = new \App\Models\Service();
        $service->name = $validated['name'];
        $service->category = $validated['category'] ?? null;
        $service->duration = $validated['duration'];
        $service->price = $validated['price'];
        $service->description = $validated['description'] ?? null;
        $service->is_active = $request->has('is_active') ? 1 : 0;
        $service->save();

        // Attach service to provider
        $provider->services()->attach($service->id);

        return redirect()
            ->route('provider.services.index')
            ->with('success', 'Service created successfully!');
    }

    public function servicesEdit(\App\Models\Service $service)
    {
        $provider = auth()->user()->provider;
        
        // Check if provider owns this service
        if (!$provider->services->contains($service->id)) {
            abort(403, 'You do not have permission to edit this service.');
        }

        return view('provider.services.edit', compact('service'));
    }

    public function servicesUpdate(Request $request, \App\Models\Service $service)
    {
        $provider = auth()->user()->provider;
        
        // Check if provider owns this service
        if (!$provider->services->contains($service->id)) {
            abort(403, 'You do not have permission to update this service.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'duration' => 'required|integer|min:5|max:480',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $service->update([
            'name' => $validated['name'],
            'category' => $validated['category'] ?? null,
            'duration' => $validated['duration'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()
            ->route('provider.services.index')
            ->with('success', 'Service updated successfully!');
    }

    public function servicesDestroy(\App\Models\Service $service)
    {
        $provider = auth()->user()->provider;
        
        // Check if provider owns this service
        if (!$provider->services->contains($service->id)) {
            abort(403, 'You do not have permission to delete this service.');
        }

        // Detach from provider first
        $provider->services()->detach($service->id);
        
        // Delete the service if no other providers are using it
        if ($service->providers()->count() == 0) {
            $service->delete();
        }

        return redirect()
            ->route('provider.services.index')
            ->with('success', 'Service deleted successfully!');
    }
}
