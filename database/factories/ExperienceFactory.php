<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $studentIds = Student::pluck('id')->toArray();

        return [
            'student_id' => $this->faker->randomElement($studentIds),
            'position' => $this->faker->jobTitle,
            'company_name' => $this->faker->company,
            'field' => $this->faker->word,
            'duration' => $this->faker->numberBetween(1, 24) . ' months',
            'description' => $this->faker->paragraph,
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->date,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
