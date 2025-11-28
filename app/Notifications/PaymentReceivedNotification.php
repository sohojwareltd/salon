<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Appointment $appointment,
        public float $amount,
        public float $tipAmount = 0
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Payment Received - Thank You!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for your payment!')
            ->line('Service: ' . $this->appointment->service->name)
            ->line('Amount Paid: ৳' . number_format($this->amount, 2));

        if ($this->tipAmount > 0) {
            $message->line('Tip Amount: ৳' . number_format($this->tipAmount, 2))
                ->line('Total: ৳' . number_format($this->amount + $this->tipAmount, 2));
        }

        return $message
            ->line('Your payment has been processed successfully.')
            ->action('Leave a Review', route('customer.review', $this->appointment))
            ->line('We appreciate your business!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'amount' => $this->amount,
            'tip_amount' => $this->tipAmount,
            'total' => $this->amount + $this->tipAmount,
            'service_name' => $this->appointment->service->name,
        ];
    }
}
