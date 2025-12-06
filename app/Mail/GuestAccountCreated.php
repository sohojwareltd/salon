<?php

namespace App\Mail;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GuestAccountCreated extends Mailable
{
    use SerializesModels;

    public $user;
    public $password;
    public $appointment;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $password, Appointment $appointment)
    {
        $this->user = $user;
        $this->password = $password;
        $this->appointment = $appointment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Your Account Has Been Created - Login Credentials Inside',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.guest-account-created',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
