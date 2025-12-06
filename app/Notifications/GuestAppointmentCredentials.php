<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GuestAppointmentCredentials extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $password;
    public $appointment;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, string $password, Appointment $appointment)
    {
        $this->user = $user;
        $this->password = $password;
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $loginUrl = route('login');
        
        // Get all service names (appointment has multiple services)
        $services = $this->appointment->services->pluck('name')->join(', ');

        return (new MailMessage)
            ->subject('Your Appointment Confirmation & Login Credentials')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your appointment has been successfully booked.')
            ->line('')
            ->line('**Appointment Details:**')
            ->line('Provider: ' . $this->appointment->provider->name)
            ->line('Service(s): ' . $services)
            ->line('Date: ' . \Carbon\Carbon::parse($this->appointment->appointment_date)->format('F d, Y'))
            ->line('Time: ' . \Carbon\Carbon::parse($this->appointment->start_time)->format('g:i A'))
            ->line('')
            ->line('**Your Login Credentials:**')
            ->line('Email: ' . $notifiable->email)
            ->line('Password: ' . $this->password)
            ->line('')
            ->line('Please save these credentials in a secure place. You can use them to login and manage your appointments.')
            ->action('Login to Your Account', $loginUrl)
            ->line('')
            ->line('**Important:** Please change your password after your first login for security purposes.')
            ->line('')
            ->line('Thank you for choosing our service!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'password' => $this->password,
        ];
    }
}
