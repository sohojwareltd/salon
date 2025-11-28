<?php

namespace App\Notifications;

use App\Models\ProviderWalletEntry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EarningsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public ProviderWalletEntry $walletEntry
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Earnings Added')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have received new earnings!')
            ->line('Service Amount: ৳' . number_format($this->walletEntry->service_amount, 2))
            ->line('Your Share: ৳' . number_format($this->walletEntry->provider_amount, 2))
            ->line('Tips: ৳' . number_format($this->walletEntry->tips_amount, 2))
            ->line('Total Earned: ৳' . number_format($this->walletEntry->total_provider_amount, 2))
            ->line('New Balance: ৳' . number_format($this->walletEntry->provider->wallet_balance, 2))
            ->action('View Wallet', route('provider.wallet'))
            ->line('Keep up the great work!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'wallet_entry_id' => $this->walletEntry->id,
            'service_amount' => $this->walletEntry->service_amount,
            'provider_amount' => $this->walletEntry->provider_amount,
            'tips_amount' => $this->walletEntry->tips_amount,
            'total_earned' => $this->walletEntry->total_provider_amount,
            'new_balance' => $this->walletEntry->provider->wallet_balance,
        ];
    }
}
