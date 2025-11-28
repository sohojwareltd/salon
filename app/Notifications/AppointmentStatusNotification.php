<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Appointment $appointment,
        public string $oldStatus,
        public string $newStatus
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
        $statusMessages = [
            'confirmed' => 'Your appointment has been confirmed!',
            'completed' => 'Your appointment has been completed!',
            'cancelled' => 'Your appointment has been cancelled.',
        ];

        $message = (new MailMessage)
            ->subject('Appointment Status Update')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($statusMessages[$this->newStatus] ?? 'Your appointment status has been updated.')
            ->line('Service: ' . $this->appointment->service->name)
            ->line('Provider: ' . $this->appointment->provider->user->name)
            ->line('Date: ' . $this->appointment->appointment_date->format('M d, Y'))
            ->line('Time: ' . \Carbon\Carbon::parse($this->appointment->start_time)->format('g:i A'));

        if ($this->newStatus === 'completed') {
            $message->action('Make Payment', route('customer.payment', $this->appointment));
        } elseif ($this->newStatus === 'confirmed') {
            $message->action('View Appointment', route('customer.bookings'));
        }

        return $message->line('Thank you for choosing our service!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'service_name' => $this->appointment->service->name,
            'appointment_date' => $this->appointment->appointment_date->toDateString(),
            'start_time' => $this->appointment->start_time,
        ];
    }
}
