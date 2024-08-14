<?php

namespace Database\Factories;

use App\Models\Lecturer;
use App\Models\Period;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $lecturerIds = Lecturer::pluck('id')->toArray();
        $year = Period::pluck('year')->toArray();
        $role = ['Dosen', 'Mahasiswa'];

        return [
            'lecturer1_id' => $this->faker->randomElement($lecturerIds),
            'lecturer2_id' => $this->faker->randomElement($lecturerIds),
            'title' => $this->faker->sentence,
            'agency' => $this->faker->company,
            'description' => $this->faker->paragraph,
            'tools' => $this->faker->word,
            'instance' => $this->faker->word,
            'year' => $this->faker->randomElement($year),
            'status' => $this->faker->randomElement(['counseling', 'revision', 'process','not taken yet']),
            'Approval_lecturer_1' => $this->faker->randomElement(['Approved', 'Not Approved', 'Not yet Approved']),
            'Approval_lecturer_2' => $this->faker->randomElement(['Approved', 'Not Approved', 'Not yet Approved']),
            'uploadedBy' => $this->faker->randomElement($role),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
