<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuratPerintahMail extends Mailable
{
    use Queueable, SerializesModels;

    public $suratKeluar;
    public $guru;
    public $filePath;

    public function __construct($suratKeluar, $guru, $filePath)
    {
        $this->suratKeluar = $suratKeluar;
        $this->guru = $guru;
        $this->filePath = $filePath;
    }

    public function build()
    {
        $metadata = json_decode($this->suratKeluar->metadata, true);

        return $this->subject('Surat Perintah: ' . $this->suratKeluar->no_surat_keluar)
            ->view('emails.surat_perintah')
            ->with([
                'nama' => $this->guru['nama'],
                'no_surat' => $this->suratKeluar->no_surat_keluar,
                'acara' => $metadata['nama_acara'] ?? '',
                'tanggal' => $metadata['tanggal'] ?? '',
                'tempat' => $metadata['tempat'] ?? '',
            ])
            ->attach($this->filePath, [
                'as' => 'Surat_Perintah.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
