<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Catatan: $role di-set lewat property assignment, bukan mass-fill,
        // karena `role` sudah dikeluarkan dari User::$fillable (anti
        // privilege escalation). Pattern ini dipakai di seluruh codebase
        // — RegisterController, walk-in flow, seeder ini.
        $this->buatUser('Test Customer', 'customer@test.com', '081234567890', 'customer');
        $this->buatUser('Test Admin', 'admin@test.com', '081234567891', 'admin');
        $this->buatUser('Test Driver', 'driver@test.com', '081234567892', 'driver');

        // Driver kedua biar DriverAssigner kelihatan jalan menyebar tugas.
        // Round-robin / load-based gak ada gunanya kalau cuma satu driver.
        $this->buatUser('Test Driver 2', 'driver2@test.com', '081234567893', 'driver');
    }

    private function buatUser(string $name, string $email, string $phone, string $role): User
    {
        $user = new User([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => Hash::make('password123'),
        ]);
        $user->role = $role;
        $user->save();

        return $user;
    }
}
