<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    /**
     * Create a new message instance.
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // S'assurer que l'email est valide, sinon utiliser l'email par défaut
        $replyToEmail = filter_var($this->details['email'] ?? '', FILTER_VALIDATE_EMAIL)
            ? $this->details['email']
            : config('mail.from.address');

        $replyToName = !empty($this->details['name']) ? $this->details['name'] : 'Visiteur';

        return new Envelope(
            subject: 'New Contact Form Message',
            replyTo: [new Address($replyToEmail, $replyToName)]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}