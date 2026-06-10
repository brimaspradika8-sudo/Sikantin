<?php

namespace App\Mail;

use App\Models\SellerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SellerApplicationApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public SellerApplication $application,
        public string $sellerEmail,
        public ?string $sellerPassword = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengajuan Penjual Anda Telah Disetujui - Sikantin',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.seller-application-approved',
        );
    }
}
