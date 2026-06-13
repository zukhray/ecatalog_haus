<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Menggunakan model User bawaan Laravel
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Haus',
            'email' => 'admin@gmail.com', // Silakan sesuaikan email login lu
            'password' => Hash::make('admin123'), // Password lu otomatis jadi: admin123
            
            // CATATAN: Kalau di tabel user lu ada kolom khusus penanda admin 
            // (seperti: 'role' => 'admin' atau 'is_admin' => 1), 
            // lu bisa tambahin barisnya di bawah sini ya bro.
        ]);
    }
}