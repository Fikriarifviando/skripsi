<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kodeSurat()
    {
        return $this->belongsTo(KodeSurat::class);
    }

    protected $fillable = ['nama', 'user_id', 'metadata', 'kode_pencarian', 'nomor_agenda', 'kode_surat_id', 'no_surat_keluar', 'gambar_ttd', 'status_nomor', 'status', 'path_file', 'nama_file'];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function getKodeRegistrasiAttribute()
    {
        return $this->metadata['kode_uniq'] ?? null;
    }

    /**
     * Accessor untuk mendapatkan jenis surat berdasarkan metadata
     * 
     * @return string
     */
    public function getJenisSuratAttribute()
    {
        $metadata = is_array($this->metadata) ? $this->metadata : json_decode($this->metadata, true);

        if (!$metadata) {
            return 'lainnya';
        }

        if (isset($metadata['guru_ditugaskan'])) {
            return 'perintah';
        } elseif (isset($metadata['informasi_acara'])) {
            return 'rekomendasi';
        } elseif (isset($metadata['keperluan'])) {
            return 'keterangan';
        } elseif (isset($metadata['acara']) && isset($metadata['kepada'])) {
            return 'undangan';
        } elseif (isset($metadata['perihal']) && isset($metadata['pembuka'])) {
            return 'pengumuman';
        } else {
            return 'lainnya';
        }
    }
}
