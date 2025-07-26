<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailSuratKeluar extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $kodeUniq;

    public function __construct($kodeUniq)
    {
        $this->kodeUniq = $kodeUniq;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Kode pencarian surat ',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.view-email',
            with: [
                'kode' => $this->kodeUniq
            ]
        );
    }

    public function attachments()
    {
        return [];
    }
}
