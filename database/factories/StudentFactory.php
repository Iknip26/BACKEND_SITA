<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
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
            'user_id' => 1,
            'NIM' => $this->faker->unique()->numerify('########'),
            'semester' => $this->faker->numberBetween(1, 8),
            'IPK' => $this->faker->randomFloat(2, 2.00, 4.00),
            'SKS' => $this->faker->numberBetween(0, 144),
            'phone_number' => $this->faker->phoneNumber,
            'sidang' => $this->faker->boolean,
            'judul' => $this->faker->boolean,
            'yudisium' => $this->faker->boolean,
            'skill' => $this->faker->word,
            'github' => $this->faker->url,
            'linkedin' => $this->faker->url,
            'cv' => $this->faker->url,
            'portofolio' => $this->faker->url,
            'website' => $this->faker->url,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
