<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Owner City Cups',
            'email' => 'owner@citycups.com', // Login dengan email ini
            'password' => Hash::make('admin123'), // Password Anda
            'role' => 'owner',
        ]);
    }
}
