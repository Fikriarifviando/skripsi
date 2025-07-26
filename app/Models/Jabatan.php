<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatans';

    protected $fillable = [
        'nama',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Relasi ke Pegawai
     */
    public function pegawais(): HasMany
    {
        return $this->hasMany(Pegawai::class);
    }

    /**
     * Mendapatkan jabatan default
     */
    public static function getDefault()
    {
        return self::where('is_default', true)->first();
    }
}
