<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuratKeteranganMail extends Mailable
{
    use Queueable, SerializesModels;

    public $suratKeluar;
    public $dataSurat;
    public $filePath;

    public function __construct($suratKeluar, $metadata, $filePath)
    {
        $this->suratKeluar = $suratKeluar;
        $this->dataSurat = $metadata;
        $this->filePath = $filePath;
    }

    public function build()
    {
        return $this->subject('Surat Keterangan: ' . $this->suratKeluar->no_surat_keluar)
            ->view('emails.surat_keterangan')
            ->with([
                'nama' => $this->dataSurat['informasi_kontak']['nama'] ?? '',
                'nisn' => $this->dataSurat['informasi_kontak']['nisn'] ?? '',
                'keterangan' => $this->dataSurat['keterangan'] ?? '',
                'no_surat' => $this->suratKeluar->no_surat_keluar,
            ])
            ->attach($this->filePath, [
                'as' => 'Surat_Keterangan.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
