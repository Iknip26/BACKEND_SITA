<?php

namespace Database\Seeders;

use App\Models\Counseling;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CounselingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Counseling::factory()->count(35)->create();
    }
}
