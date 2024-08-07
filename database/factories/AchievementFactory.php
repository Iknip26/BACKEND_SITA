<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Achievement>
 */
class AchievementFactory extends Factory
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
            'achievement_name' => $this->faker->sentence,
            'achievement_type' => $this->faker->word,
            'achievement_level' => $this->faker->word,
            'achievement_year' => $this->faker->year,
            'description' => $this->faker->paragraph,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
