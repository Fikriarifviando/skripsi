<?php

namespace App\Services;

use App\Models\SuratKeluar;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SuratGeneratorService
{
    /**
     * Generate surat berdasarkan jenis
     */
    public function generateSurat(SuratKeluar $suratKeluar)
    {
        try {
            switch ($suratKeluar->jenis_surat) {
                case 'perintah':
                    return $this->generateSuratPerintahPDF($suratKeluar);
                case 'rekomendasi':
                    return $this->generateSuratRekomendasiPDF($suratKeluar);
                case 'keterangan':
                    return $this->generateSuratKeteranganPDF($suratKeluar);
                case 'undangan':
                    return $this->generateSuratUndanganPDF($suratKeluar);
                case 'pengumuman':
                    return $this->generateSuratpengumumanPDF($suratKeluar);
                default:
                    throw new \Exception("Jenis surat tidak dikenali");
            }
        } catch (\Exception $e) {
            Log::error('Error generating surat: ' . $e->getMessage(), [
                'surat_keluar_id' => $suratKeluar->id,
                'jenis_surat' => $suratKeluar->jenis_surat,
            ]);

            throw $e; // biar controller yang nangani
        }
    }

    /**
     * Generate surat perintah PDF
     */
    public function generateSuratPerintahPDF($suratKeluar)
    {
        $kepalaSekolah = $this->getKepalaSekolahData();

        // Ambil path gambar tanda tangan menggunakan Storage
        $ttdPath = null;
        if ($suratKeluar->gambar_ttd && Storage::disk('public')->exists($suratKeluar->gambar_ttd)) {
            $ttdPath = storage_path('app/public/' . $suratKeluar->gambar_ttd);
        }

        // Decode metadata jadi array
        $metadata = json_decode($suratKeluar->metadata, true);

        // Load view dengan semua data
        $pdf = PDF::loadView('pdf.surat_perintah', [
            'no_surat' => $suratKeluar->no_surat_keluar,
            'kepala_sekolah' => (object)$kepalaSekolah,
            'metadata' => $metadata,
            'ttd_path' => $ttdPath,
        ]);


        return $this->savePDF($suratKeluar, $pdf, 'Surat_Perintah');
    }

    /**
     * Generate surat rekomendasi PDF
     */
    public function generateSuratRekomendasiPDF(SuratKeluar $suratKeluar)
    {
        // Ambil data kepala sekolah
        $kepalaSekolah = $this->getKepalaSekolahData();

        // Ambil path gambar tanda tangan menggunakan Storage
        $ttdPath = null;
        if ($suratKeluar->gambar_ttd && Storage::disk('public')->exists($suratKeluar->gambar_ttd)) {
            $ttdPath = storage_path('app/public/' . $suratKeluar->gambar_ttd);
        }

        // Decode metadata jadi array
        $metadata = json_decode($suratKeluar->metadata, true);

        // Load view dengan semua data
        $pdf = PDF::loadView('pdf.surat_rekomendasi', [
            'no_surat' => $suratKeluar->no_surat_keluar,
            'kepala_sekolah' => (object)$kepalaSekolah,
            'metadata' => $metadata,
            'ttd_path' => $ttdPath,
        ]);


        return $this->savePDF($suratKeluar, $pdf, 'Surat_Rekomendasi');
    }

    /**
     * Generate surat keterangan PDF
     */
    public function generateSuratKeteranganPDF(SuratKeluar $suratKeluar)
    {
        // Ambil data kepala sekolah
        $kepalaSekolah = $this->getKepalaSekolahData();

        // Ambil path gambar tanda tangan menggunakan Storage
        $ttdPath = null;
        if ($suratKeluar->gambar_ttd && Storage::disk('public')->exists($suratKeluar->gambar_ttd)) {
            $ttdPath = storage_path('app/public/' . $suratKeluar->gambar_ttd);
        }

        // Decode metadata jadi array
        $metadata = json_decode($suratKeluar->metadata, true);

        // Load view dengan semua data
        $pdf = PDF::loadView('pdf.surat_keterangan', [
            'no_surat' => $suratKeluar->no_surat_keluar,
            'kepala_sekolah' => (object)$kepalaSekolah,
            'metadata' => $metadata,
            'ttd_path' => $ttdPath,
        ]);


        return $this->savePDF($suratKeluar, $pdf, 'Surat_Keterangan');
    }

    /**
     * Fungsi helper untuk menyimpan PDF
     */
    protected function savePDF(SuratKeluar $suratKeluar, $pdf, $prefixNama)
    {
        // Buat nama file
        $fileName = $prefixNama . '_' . str_replace('/', '_', $suratKeluar->no_surat_keluar) . '.pdf';

        // Tentukan path penyimpanan
        $path = 'surat_keluar/' . date('Y/m/');

        // Pastikan direktori ada
        Storage::disk('public')->makeDirectory($path);

        // Path lengkap file untuk disimpan
        $fullPath = $path . $fileName;

        // Simpan file PDF menggunakan Storage
        Storage::disk('public')->put($fullPath, $pdf->output());

        // Update record surat keluar dengan path dan nama file
        $suratKeluar->update([
            'path_file' => $path,
            'nama_file' => $fileName,
        ]);

        return [
            'path' => storage_path('app/public/' . $fullPath),
            'url' => asset('storage/' . $fullPath), // pakai helper `asset()` untuk generate link public
        ];
    }

    /**
     * Generate surat pengumuman PDF
     */
    public function generateSuratPengumumanPDF(SuratKeluar $suratKeluar)
    {
        $kepalaSekolah = $this->getKepalaSekolahData();

        $ttdPath = null;
        if ($suratKeluar->gambar_ttd && Storage::disk('public')->exists($suratKeluar->gambar_ttd)) {
            $ttdPath = storage_path('app/public/' . $suratKeluar->gambar_ttd);
        }

        $metadata = json_decode($suratKeluar->metadata, true);

        $pdf = PDF::loadView('pdf.surat_pengumuman', [
            'no_surat' => $suratKeluar->no_surat_keluar,
            'kepala_sekolah' => (object) $kepalaSekolah,
            'metadata' => $metadata,
            'ttd_path' => $ttdPath,
        ]);

        return $this->savePDF($suratKeluar, $pdf, 'Surat_Pengumuman');
    }

    /**
     * Generate surat undangan PDF
     */
    public function generateSuratUndanganPDF(SuratKeluar $suratKeluar)
    {
        $kepalaSekolah = $this->getKepalaSekolahData();

        $ttdPath = null;
        if ($suratKeluar->gambar_ttd && Storage::disk('public')->exists($suratKeluar->gambar_ttd)) {
            $ttdPath = storage_path('app/public/' . $suratKeluar->gambar_ttd);
        }

        $metadata = json_decode($suratKeluar->metadata, true);

        $pdf = PDF::loadView('pdf.surat_undangan', [
            'no_surat' => $suratKeluar->no_surat_keluar,
            'kepala_sekolah' => (object) $kepalaSekolah,
            'metadata' => $metadata,
            'ttd_path' => $ttdPath,
        ]);

        return $this->savePDF($suratKeluar, $pdf, 'Surat_Undangan');
    }

    /**
     * Ambil data kepala sekolah
     */
    private function getKepalaSekolahData()
    {
        return [
            'nama' => 'Darto, M.Pd.',
            'nip' => '197012062008011010',
            'jabatan' => 'Plt. Kepala Sekolah',
            'pangkat' => 'Pembina (IV/a)'
        ];
    }
}
