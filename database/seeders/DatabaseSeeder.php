<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin Kredit
        User::create([
            'name' => 'Admin Kredit',
            'username' => 'adminkredit',
            'email' => 'admin.kredit@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'), // Ganti dengan password yang aman
            'role' => '1',
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Developer Rumah
        User::create([
            'name' => 'Developer Rumah',
            'username' => 'developer',
            'email' => 'developer.rumah@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('developer123'), // Ganti dengan password yang aman
            'role' => '2',
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Test User
        User::create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('tes123'), // Ganti dengan password yang aman
            'role' => '1',
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
