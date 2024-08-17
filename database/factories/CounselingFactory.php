<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Counseling>
 */
class CounselingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $projectIds = Project::pluck('id')->toArray();
        return [
            //
            'project_id' => $this->faker->randomElement($projectIds),
            'date' => $this->faker->date,
            'subject' => $this->faker->sentence(),
            'progress' => $this->faker->numberBetween(10,100),
            'status'=> $this->faker->randomElement(['revision','ok']),
            'lecturer_note' => $this->faker->paragraph,
            'description' => $this->faker->paragraph,
            // 'file' => $this->faker->filePath(),
        ];
    }
}
