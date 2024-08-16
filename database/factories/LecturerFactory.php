<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lecturer>
 */
class LecturerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'user_id' => null,
            'front_title' => $this->faker->randomElement(['Dr.', 'Prof.','']),
            'back_title' => $this->faker->randomElement(['PhD', 'MSc']),
            'NID' => $this->faker->unique()->numerify('########'),
            'photo_profile' => $this->faker->imageUrl(),
            'max_quota' => 10,
            'remaining_quota' => 10,
            'phone_number' => $this->faker->phoneNumber,
            'isKaprodi' => $this->faker->boolean,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
