<?php

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactFormSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Contact Form Submission')
            ->greeting('Hello Admin!')
            ->line('You have received a new contact form submission.')
            ->line('**Name:** ' . $this->contact->name)
            ->line('**Email:** ' . $this->contact->email)
            ->line('**Subject:** ' . $this->contact->subject)
            ->line('**Message:**')
            ->line($this->contact->message)
            ->action('View in Admin Panel', url('/admin/contacts/' . $this->contact->id))
            ->line('Please respond to the customer as soon as possible.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'contact_id' => $this->contact->id,
            'name' => $this->contact->name,
            'email' => $this->contact->email,
            'subject' => $this->contact->subject,
        ];
    }
}