<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentApiController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Create payment intent for an appointment
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:appointments,id',
            'tip_amount' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $appointment = Appointment::findOrFail($request->appointment_id);

        // Verify appointment is completed and payment is pending
        if ($appointment->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Payment can only be made for completed appointments',
            ], 400);
        }

        if ($appointment->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'This appointment has already been paid',
            ], 400);
        }

        try {
            $result = $this->paymentService->createPaymentIntent(
                $appointment,
                $request->tip_amount ?? 0
            );

            return response()->json([
                'success' => true,
                'message' => 'Payment intent created successfully',
                'data' => [
                    'payment' => $result['payment'],
                    'client_secret' => $result['client_secret'],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment intent',
            ], 500);
        }
    }

    /**
     * Confirm payment after successful Stripe charge
     */
    public function confirm(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'charge_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payment = Payment::findOrFail($id);

        try {
            $this->paymentService->confirmPayment($payment, $request->charge_id);

            return response()->json([
                'success' => true,
                'message' => 'Payment confirmed successfully',
                'data' => $payment->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm payment',
            ], 500);
        }
    }

    /**
     * Display the specified payment
     */
    public function show(string $id)
    {
        $payment = Payment::with(['appointment.service', 'user'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $payment,
        ]);
    }
}
