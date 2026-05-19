<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'phone' => '081234567890',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'phone' => '081234567891',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Test Driver',
            'email' => 'driver@test.com',
            'phone' => '081234567892',
            'password' => Hash::make('password123'),
            'role' => 'driver',
        ]);
    }
}