<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data existing
        DB::table('users')->delete();

        // Buat user default
        User::factory()->create([
            'name' => 'Superadmin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('SuperAdmin'),
            'role' => 'superadmin'
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('Admin2024!'),
            'role' => 'admin'
        ]);

        // Tambahkan user random
        User::factory(10)->create();
    }
}
