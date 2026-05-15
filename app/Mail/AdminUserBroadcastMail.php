<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminUserBroadcastMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $emailSubject,
        public string $bodyText,
        public string $recipientName
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                (string) config('mail.noreply.address'),
                (string) config('mail.noreply.name')
            ),
            subject: $this->emailSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-user-broadcast',
            with: [
                'emailSubject' => $this->emailSubject,
                'bodyText' => $this->bodyText,
                'recipientName' => $this->recipientName,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
