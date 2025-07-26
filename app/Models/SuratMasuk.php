<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kode_surat_id',
        'no_surat',
        'perihal',
        'tgl_terima',
        'asal_surat',
        'path_file',
        'nama_file',
        'status_disposisi'
    ];

    

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function kodeSurat()
    {
        return $this->belongsTo(KodeSurat::class);
    }

    public function pegawais()
    {
        return $this->belongsToMany(Pegawai::class, 'disposisi_surats', 'surat_masuk_id', 'pegawai_id')
            ->withPivot('catatan')
            ->withTimestamps();
    }

    // public function pegawais() : BelongsToMany
    // {
    //     return $this->belongsToMany(Pegawai::class, 'surat_masuk_id')
    //         ->withPivot('catatan')
    //         ->withTimestamps();
    // }
    
}
