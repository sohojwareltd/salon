<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReviewApiController extends Controller
{
    /**
     * Store a new review
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|exists:providers,id',
            'appointment_id' => 'required|exists:appointments,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check if review already exists for this appointment
        $existingReview = Review::where('appointment_id', $request->appointment_id)->first();
        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this appointment',
            ], 400);
        }

        try {
            DB::beginTransaction();

            $review = Review::create([
                'user_id' => $request->user()->id,
                'provider_id' => $request->provider_id,
                'appointment_id' => $request->appointment_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            // Update provider's average rating
            $provider = Provider::findOrFail($request->provider_id);
            $avgRating = $provider->reviews()->avg('rating');
            $totalReviews = $provider->reviews()->count();

            $provider->update([
                'average_rating' => round($avgRating, 2),
                'total_reviews' => $totalReviews,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully',
                'data' => $review->load('user'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit review',
            ], 500);
        }
    }

    /**
     * Get reviews for a provider
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'provider', 'appointment.service'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('provider_id')) {
            $query->where('provider_id', $request->provider_id);
        }

        $reviews = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $reviews,
        ]);
    }

    /**
     * Display the specified review
     */
    public function show(string $id)
    {
        $review = Review::with(['user', 'provider', 'appointment.service'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $review,
        ]);
    }
}
