<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data existing
        User::truncate();

        // Buat user default
        User::factory()->create([
            'name' => 'Superadmin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('SuperAdmin2024!'),
            'role' => 'superadmin'
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('Admin2024!'),
            'role' => 'admin'
        ]);

        // Tambahkan user random
        User::factory(50)->create();
    }
}
