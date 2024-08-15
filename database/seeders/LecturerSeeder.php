<?php

namespace Database\Seeders;

use App\Models\Lecturer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LecturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Lecturer::factory()->create([
            'user_id' => 2,
        ]);

        Lecturer::factory()->create([
            'user_id' => 3,
        ]);
                foreach (range(4, 13) as $userId) {
                    Lecturer::factory()->create(['user_id' => $userId]);
                }
    }
}
