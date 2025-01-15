<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => $this->faker->randomElement(['user', 'admin']),
            'remember_token' => Str::random(10),
        ];
    }

    // Optional: Method untuk state spesifik
    public function admin()
    {
        return $this->state([
            'role' => 'admin'
        ]);
    }

    public function user()
    {
        return $this->state([
            'role' => 'user'
        ]);
    }
}
