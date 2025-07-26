<?php

namespace App\Services;

use App\Models\SuratKeluar;
use App\Mail\SuratPerintahMail;
use App\Mail\SuratRekomendasiMail;
use App\Mail\SuratKeteranganMail;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Fungsi untuk mengirim surat
     */
    public function kirimSurat(SuratKeluar $suratKeluar, $pdfInfo)
    {
        $metadata = json_decode($suratKeluar->metadata, true);
        $emailsSent = 0;

        // Kirim surat berdasarkan jenis surat
        switch ($suratKeluar->jenis_surat) {
            case 'perintah':
                $emailsSent = $this->kirimSuratPerintah($suratKeluar, $metadata, $pdfInfo);
                break;

            case 'rekomendasi':
                $emailsSent = $this->kirimSuratRekomendasi($suratKeluar, $metadata, $pdfInfo);
                break;

            case 'keterangan':
                $emailsSent = $this->kirimSuratKeterangan($suratKeluar, $metadata, $pdfInfo);
                break;
            
            
        }

        return $emailsSent > 0;
    }

    /**
     * Kirim surat perintah
     */
    private function kirimSuratPerintah(SuratKeluar $suratKeluar, $metadata, $pdfInfo)
    {
        $emailsSent = 0;
        // Kirim ke multiple guru
        foreach ($metadata['guru_ditugaskan'] as $guru) {
            if (!empty($guru['email'])) {
                Mail::to($guru['email'])->send(
                    new SuratPerintahMail($suratKeluar, $guru, $pdfInfo['path'])
                );
                $emailsSent++;
            }
        }
        return $emailsSent;
    }

    /**
     * Kirim surat rekomendasi
     */
    private function kirimSuratRekomendasi(SuratKeluar $suratKeluar, $metadata, $pdfInfo)
    {
        $emailsSent = 0;
        // Kirim ke siswa
        if (!empty($metadata['informasi_kontak']['email'])) {
            Mail::to($metadata['informasi_kontak']['email'])->send(
                new SuratRekomendasiMail($suratKeluar, $metadata, $pdfInfo['path'])
            );
            $emailsSent++;
        }
        return $emailsSent;
    }

    /**
     * Kirim surat keterangan
     */
    private function kirimSuratKeterangan(SuratKeluar $suratKeluar, $metadata, $pdfInfo)
    {
        $emailsSent = 0;
        // Kirim ke siswa
        if (!empty($metadata['informasi_kontak']['email'])) {
            Mail::to($metadata['informasi_kontak']['email'])->send(
                new SuratKeteranganMail($suratKeluar, $metadata, $pdfInfo['path'])
            );
            $emailsSent++;
        }
        return $emailsSent;
    }
}
