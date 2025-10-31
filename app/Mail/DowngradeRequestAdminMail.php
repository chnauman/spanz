<?php

namespace App\Mail;

use App\Models\DowngradeRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DowngradeRequestAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $request;

    public function __construct(DowngradeRequest $request)
    {
        $this->request = $request;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Downgrade Request Submitted',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.downgrade-request-admin',
            with: [
                'request' => $this->request,
                'user' => $this->request->user,
                'currentSubscription' => $this->request->currentSubscription,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}



