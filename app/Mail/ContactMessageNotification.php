<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageNotification extends Mailable
{
    use Queueable, SerializesModels;

    public ContactMessage $contactMessage;

    public function __construct(ContactMessage $contactMessage)
    {
        $this->contactMessage = $contactMessage;
    }

    public function envelope(): Envelope
    {
        $subject = $this->contactMessage->subject ?: 'New Portfolio Contact Message';
        return new Envelope(
            subject: '🔔 ' . $subject . ' - From ' . $this->contactMessage->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

