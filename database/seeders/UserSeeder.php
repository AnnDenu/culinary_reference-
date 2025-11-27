<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'avatar' => 'default.jpg'
        ]);

        User::create([
            'username' => 'user',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'avatar' => 'default.jpg'
        ]);
    }
} 