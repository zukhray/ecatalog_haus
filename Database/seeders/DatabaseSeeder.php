<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Haus',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
        ]);

        // Jalankan seeder pesanan
        $this->call(PesananSeeder::class);
    }
}