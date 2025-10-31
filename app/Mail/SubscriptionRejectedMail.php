<?php

namespace App\Mail;

use App\Models\SubscriptionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $request;

    public function __construct(SubscriptionRequest $request)
    {
        $this->request = $request;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Subscription Request Was Not Approved',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription-rejected',
            with: [
                'request' => $this->request,
                'user' => $this->request->user,
                'subscription' => $this->request->subscription,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}



