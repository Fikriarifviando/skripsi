<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KodeSurat extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_klasifikasi',
        'nama_kode'
    ];


    public function suratMasuk(): HasMany
    {
        return $this->hasMany(SuratMasuk::class);
    }

    public function suratKeluar(): HasMany
    {
        return $this->hasMany(SuratKeluar::class);
    }
}
