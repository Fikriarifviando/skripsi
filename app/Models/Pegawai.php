<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawais';

    protected $fillable = [
        'nip',
        'email',
        'nama',
        'jabatan_id', // Pastikan ada field ini
    ];

    /**
     * Relasi ke Jabatan
     */
    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    /**
     * Relasi ke surat masuk melalui disposisi
     */
    public function suratMasuks(): BelongsToMany
    {
        return $this->belongsToMany(SuratMasuk::class, 'disposisi_surats', 'pegawai_id', 'surat_masuk_id')
            ->withPivot('catatan')
            ->withTimestamps();
    }

    /**
     * Set jabatan_id default jika tidak diisi
     */
    protected static function booted()
    {
        static::creating(function ($pegawai) {
            if (empty($pegawai->jabatan_id)) {
                $defaultJabatan = Jabatan::getDefault();
                if ($defaultJabatan) {
                    $pegawai->jabatan_id = $defaultJabatan->id;
                }
            }
        });
    }
}
