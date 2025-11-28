<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Services\WalletService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateEarningsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Appointment $appointment,
        public float $tipAmount = 0
    ) {}

    /**
     * Execute the job.
     */
    public function handle(WalletService $walletService): void
    {
        // Create wallet entry and update provider balance
        $walletService->createWalletEntry(
            $this->appointment,
            $this->tipAmount
        );

        // Update appointment paid status
        $this->appointment->update([
            'paid_at' => now(),
        ]);
    }
}
