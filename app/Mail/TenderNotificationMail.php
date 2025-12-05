<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Tender;

class TenderNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tenders;
    public $userName;
    public $frequency;
    public $period;

    /**
     * Create a new message instance.
     */
    public function __construct($tenders, $userName = null, $frequency = 'daily', $period = '')
    {
        $this->tenders = $tenders;
        $this->userName = $userName;
        $this->frequency = $frequency;
        $this->period = $period;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'New Tenders Matching Your Interests - Spanz';
        if ($this->frequency === 'weekly') {
            $subject = 'Weekly Tender Summary - Spanz';
        } elseif ($this->frequency === 'monthly') {
            $subject = 'Monthly Tender Summary - Spanz';
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tender-notification',
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

