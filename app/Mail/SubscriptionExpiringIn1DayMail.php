<?php

namespace App\Mail;

use App\Models\UserSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiringIn1DayMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userSubscription;

    /**
     * Create a new message instance.
     */
    public function __construct(UserSubscription $userSubscription)
    {
        $this->userSubscription = $userSubscription;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Subscription Expires Tomorrow - Last Chance to Renew',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription-expiring-1-day',
            with: [
                'user' => $this->userSubscription->user,
                'subscription' => $this->userSubscription->subscription,
                'expiresAt' => $this->userSubscription->expires_at,
            ]
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
