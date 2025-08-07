<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'name' => 'AdminMiddleware',
            'surname' => 'Example',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345'), // şifre: password
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        // Normal kullanıcı
        User::create([
            'name' => 'Test',
            'surname' => 'User',
            'email' => 'test@example.com',
            'password' => Hash::make('12345'), // şifre: password
            'role' => 'user',
            'is_active' => true,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

    }
}
