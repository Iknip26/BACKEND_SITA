<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'mahasiswa_user',
                'first_name' => 'Mahasiswa',
                'last_name' => 'User',
                'email' => 'mahasiswa@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'dosen_user1',
                'first_name' => 'Dosen1',
                'last_name' => 'User',
                'email' => 'dosen1@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Dosen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'dosen_user2',
                'first_name' => 'Dosen2',
                'last_name' => 'User',
                'email' => 'dosen2@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Dosen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        User::factory()->count(10)->create([
            'role' => 'Dosen',
        ]);
    }
}
