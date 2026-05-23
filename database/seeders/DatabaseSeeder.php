<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // FIX TOTAL: Data master admin utama dibungkus di dalam struktur class resmi
        User::create([
            'name'     => 'Mufaa Gallagher',
            'username' => 'mufaa',
            'email'    => 'admin@bibliora.com',
            'password' => Hash::make('password123'), // Otomatis enkripsi password biar aman
            'role'     => 'admin', // Mengunci role admin agar lolos gate web.php
            'no_telp'  => '08123456789',
            'alamat'   => 'Library HQ Terminal'
        ]);
    }
}