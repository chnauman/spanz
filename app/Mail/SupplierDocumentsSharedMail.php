<?php

namespace App\Mail;

use App\Models\SupplierDocumentShare;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupplierDocumentsSharedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public SupplierDocumentShare $share,
        public User $recipient,
        public string $dashboardUrl
    ) {}

    public function envelope(): Envelope
    {
        $senderName = $this->share->sender?->companyDetail?->company_name
            ?? $this->share->sender?->name
            ?? 'A SPANZ user';

        return new Envelope(
            subject: 'Documents shared with you — ' . $senderName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.supplier-documents-shared',
        );
    }

    /**
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
