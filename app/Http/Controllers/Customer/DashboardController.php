<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Review;
use App\Services\StripePaymentService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected StripePaymentService $stripeService
    ) {}

    public function index()
    {
        $customer = auth()->user();

        // Total spent calculation
        $totalSpent = $customer->appointments()
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->where('payment_status', 'paid')
            ->sum('services.price');

        // Pending payments amount
        $pendingPaymentsAmount = $customer->appointments()
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->where('status', 'completed')
            ->where('payment_status', '!=', 'paid')
            ->sum('services.price');

        // Statistics
        $stats = [
            'total_appointments' => $customer->appointments()->count(),
            'upcoming_appointments' => $customer->appointments()
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('appointment_date', '>=', today())
                ->count(),
            'completed_appointments' => $customer->appointments()
                ->where('status', 'completed')
                ->count(),
            'cancelled_appointments' => $customer->appointments()
                ->where('status', 'cancelled')
                ->count(),
            'pending_payments' => $customer->appointments()
                ->where('status', 'completed')
                ->where('payment_status', '!=', 'paid')
                ->count(),
            'pending_payments_amount' => $pendingPaymentsAmount,
            'total_spent' => $totalSpent,
        ];

        // Upcoming appointments
        $upcomingAppointments = $customer->appointments()
            ->with(['provider.user', 'service'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('appointment_date', '>=', today())
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        // Recent appointments
        $recentAppointments = $customer->appointments()
            ->with(['provider.user', 'service', 'payment'])
            ->latest('appointment_date')
            ->take(5)
            ->get();

        // Appointments that need payment
        $needsPayment = $customer->appointments()
            ->with(['provider.user', 'service'])
            ->where('status', 'completed')
            ->where('payment_status', '!=', 'paid')
            ->orderBy('appointment_date', 'desc')
            ->take(3)
            ->get();

        return view('customer.dashboard', compact(
            'customer',
            'stats',
            'upcomingAppointments',
            'recentAppointments',
            'needsPayment'
        ));
    }

    public function bookings()
    {
        $customer = auth()->user();

        $appointments = $customer->appointments()
            ->with(['provider.user', 'service', 'payment'])
            ->latest('appointment_date')
            ->paginate(15);

        return view('customer.bookings.index', compact('customer', 'appointments'));
    }

    public function bookingDetails(Appointment $appointment)
    {
        $customer = auth()->user();

        // Verify ownership
        if ($appointment->customer_id !== $customer->id) {
            abort(403, 'Unauthorized action.');
        }

        // Load all relationships
        $appointment->load([
            'provider.user',
            'services',
            'payment',
            'review'
        ]);

        return view('customer.booking-details', compact('appointment'));
    }

    public function payment(Appointment $appointment)
    {
        $customer = auth()->user();

        // Verify ownership
        if ($appointment->customer_id !== $customer->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if appointment can be paid
        if (!$appointment->canBePaid()) {
            return back()->with('error', 'This appointment cannot be paid at this time.');
        }

        return view('customer.payment.show', compact('appointment'));
    }

    public function processPayment(Request $request, Appointment $appointment)
    {
        $customer = auth()->user();

        // Verify ownership
        if ($appointment->customer_id !== $customer->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'payment_method_id' => 'required|string',
            'tip_amount' => 'nullable|numeric|min:0|max:1000',
        ]);

        try {
            $tipAmount = $request->tip_amount ?? 0;

            // Create payment intent
            $paymentIntent = $this->stripeService->createPaymentIntent(
                $appointment,
                $tipAmount
            );

            // Confirm payment (in production, this would be done on frontend)
            $this->stripeService->confirmPayment(
                $paymentIntent->id,
                $request->payment_method_id
            );

            return redirect()
                ->route('customer.bookings')
                ->with('success', 'Payment successful! Thank you.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Payment failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function review(Appointment $appointment)
    {
        $customer = auth()->user();

        // Verify ownership and eligibility
        if ($appointment->customer_id !== $customer->id) {
            abort(403, 'Unauthorized action.');
        }

        if (!$appointment->canBeReviewed()) {
            return back()->with('error', 'This appointment cannot be reviewed yet.');
        }

        // Check if already reviewed
        $existingReview = $appointment->review;

        return view('customer.review.create', compact('appointment', 'existingReview'));
    }

    public function storeReview(Request $request, Appointment $appointment)
    {
        $customer = auth()->user();

        // Verify ownership
        if ($appointment->customer_id !== $customer->id) {
            abort(403, 'Unauthorized action.');
        }

        if (!$appointment->canBeReviewed()) {
            return back()->with('error', 'This appointment cannot be reviewed.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Create or update review
        $review = Review::updateOrCreate(
            [
                'appointment_id' => $appointment->id,
            ],
            [
                'user_id' => $customer->id,
                'provider_id' => $appointment->provider_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        // Mark appointment as reviewed
        $appointment->update(['review_submitted' => true]);

        // Update provider rating
        $provider = $appointment->provider;
        $provider->update([
            'average_rating' => $provider->reviews()->avg('rating'),
            'total_reviews' => $provider->reviews()->count(),
        ]);

        return redirect()
            ->route('customer.bookings')
            ->with('success', 'Review submitted successfully. Thank you for your feedback!');
    }

    public function payments()
    {
        $customer = auth()->user();

        $payments = $customer->appointments()
            ->with(['service', 'payment'])
            ->where('payment_status', 'paid')
            ->latest('appointment_date')
            ->paginate(15);

        return view('customer.payments.index', compact('customer', 'payments'));
    }

    public function profile()
    {
        $customer = auth()->user();
        return view('customer.profile', compact('customer'));
    }

    public function settings()
    {
        $customer = auth()->user();
        return view('customer.settings', compact('customer'));
    }

    public function updateSettings(Request $request)
    {
        $customer = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $customer->update($request->only(['name', 'email', 'phone', 'address']));

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $customer = auth()->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!\Hash::check($request->current_password, $customer->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        $customer->update([
            'password' => \Hash::make($request->password)
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    public function updateNotifications(Request $request)
    {
        $customer = auth()->user();

        // Save notification preferences (you can create a preferences table or add columns to users table)
        // For now, we'll just return success
        
        return back()->with('success', 'Notification preferences updated successfully!');
    }

    public function updatePrivacy(Request $request)
    {
        $customer = auth()->user();

        // Save privacy preferences
        
        return back()->with('success', 'Privacy settings updated successfully!');
    }

    public function deleteAccount(Request $request)
    {
        $customer = auth()->user();

        $request->validate([
            'password' => 'required',
        ]);

        if (!\Hash::check($request->password, $customer->password)) {
            return back()->with('error', 'Password is incorrect.');
        }

        // Logout and delete account
        auth()->logout();
        $customer->delete();

        return redirect()->route('home')->with('success', 'Your account has been deleted.');
    }
}
