<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuratRekomendasiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $suratKeluar;
    public $dataSurat; // Ganti nama dari metadata menjadi dataSurat
    public $filePath;

    public function __construct($suratKeluar, $metadata, $filePath)
    {
        $this->suratKeluar = $suratKeluar;
        $this->dataSurat = $metadata; // Simpan metadata dengan nama variabel berbeda
        $this->filePath = $filePath;
    }

    public function build()
    {
        return $this->subject('Surat Rekomendasi')
            ->view('emails.surat_rekomendasi')
            ->attach($this->filePath, [
                'as' => 'Surat_Rekomendasi.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
