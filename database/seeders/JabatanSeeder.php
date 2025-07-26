<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatans = [
            ['nama' => 'Kepala Sekolah', 'is_default' => false],
            ['nama' => 'Wakasek Humas', 'is_default' => false],
            ['nama' => 'Wakasek Sapra', 'is_default' => false],
            ['nama' => 'Wakasek Kurikulum', 'is_default' => false],
            ['nama' => 'Wakasek Kesiswaan', 'is_default' => false],
            ['nama' => 'Guru', 'is_default' => true], // Default jabatan
            ['nama' => 'Kepala TU', 'is_default' => false],
            ['nama' => 'Staff TU', 'is_default' => false],
            ['nama' => 'Kepala Laboratorium', 'is_default' => false],
            ['nama' => 'Bendahara', 'is_default' => false],
            ['nama' => 'Kepala Koperasi', 'is_default' => false],
        ];

        foreach ($jabatans as $jabatan) {
            Jabatan::create($jabatan);
        }
    }
}
