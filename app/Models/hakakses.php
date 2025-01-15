<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hakakses extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    // Tambahkan default value untuk role
    protected $attributes = [
        'role' => 'user',
    ];

   
}
