<?php

namespace App\Mail;

use App\Models\SubscriptionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subscriptionRequest;
    public $type;

    /**
     * Create a new message instance.
     */
    public function __construct(SubscriptionRequest $subscriptionRequest, $type)
    {
        $this->subscriptionRequest = $subscriptionRequest;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match($this->type) {
            'new_request' => 'New Subscription Request - SPANZ',
            'approved' => 'Subscription Request Approved - SPANZ',
            'declined' => 'Subscription Request Declined - SPANZ',
            default => 'Subscription Update - SPANZ'
        };

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = match($this->type) {
            'new_request' => 'emails.subscription-request',
            'approved' => 'emails.subscription-approved',
            'declined' => 'emails.subscription-declined',
            default => 'emails.subscription-request'
        };

        return new Content(
            view: $view,
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
