<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Notifications\ReviewRequestNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendReviewRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Appointment $appointment
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Check if appointment is paid and not reviewed
        if ($this->appointment->paid_at && !$this->appointment->review_submitted) {
            // Mark as review requested
            $this->appointment->update([
                'review_requested' => true,
            ]);

            // Send notification to customer
            $this->appointment->customer->notify(
                new ReviewRequestNotification($this->appointment)
            );
        }
    }

    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil(): \DateTime
    {
        return now()->addHours(24);
    }
}
