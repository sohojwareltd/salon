<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Appointment $appointment
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
        return (new MailMessage)
            ->subject('How was your experience?')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We hope you enjoyed your recent appointment!')
            ->line('Service: ' . $this->appointment->service->name)
            ->line('Provider: ' . $this->appointment->provider->user->name)
            ->line('Date: ' . $this->appointment->appointment_date->format('M d, Y'))
            ->line('We would love to hear about your experience.')
            ->action('Leave a Review', route('customer.review', $this->appointment))
            ->line('Your feedback helps us improve our services!')
            ->line('Thank you for choosing us!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'service_name' => $this->appointment->service->name,
            'provider_name' => $this->appointment->provider->user->name,
            'appointment_date' => $this->appointment->appointment_date->toDateString(),
        ];
    }
}
