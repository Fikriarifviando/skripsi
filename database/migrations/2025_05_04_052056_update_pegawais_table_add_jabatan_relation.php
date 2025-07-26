<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan ada jabatan default jika belum ada
        $defaultExists = DB::table('jabatans')->where('is_default', true)->exists();
        if (!$defaultExists) {
            DB::table('jabatans')->insert([
                'nama' => 'Guru',
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Ambil jabatan default untuk kasus pegawai tanpa jabatan
        $defaultJabatan = DB::table('jabatans')->where('is_default', true)->first();

        // Set jabatan_id default untuk semua pegawai yang jabatan_id-nya NULL
        DB::table('pegawais')
            ->whereNull('jabatan_id')
            ->update(['jabatan_id' => $defaultJabatan->id]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu mengembalikan perubahan karena ini hanya mengisi data
    }
};
