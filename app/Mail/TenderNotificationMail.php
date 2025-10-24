<?php

namespace App\Mail;

use App\Models\Tender;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TenderNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tender;
    public $user;
    public $matchReason;

    /**
     * Create a new message instance.
     */
    public function __construct(Tender $tender, User $user, string $matchReason)
    {
        $this->tender = $tender;
        $this->user = $user;
        $this->matchReason = $matchReason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Tender Matching Your Interests - ' . $this->tender->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tender-notification',
            with: [
                'tender' => $this->tender,
                'user' => $this->user,
                'matchReason' => $this->matchReason,
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
