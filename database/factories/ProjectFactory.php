<?php

namespace Database\Factories;

use App\Models\Lecturer;
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

        return [
            'lecturer_id' => $this->faker->randomElement($lecturerIds),
            'title' => $this->faker->sentence,
            'agency' => $this->faker->company,
            'description' => $this->faker->paragraph,
            'tools' => $this->faker->word,
            'instance' => $this->faker->word,
            'status' => $this->faker->randomElement(['bimbingan', 'revisi', 'proses']),
            'Approval' => $this->faker->randomElement(['Approved', 'Not Approved', 'Not yet Approved']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
