<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Period>
 */
class PeriodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $semester = ['ganjil','genap'];
        $status = ['inProgress', 'ended'];
        return [
            //
            'semester'=> $this->faker->randomElement($semester),
            'year' => $this->faker->year,
            'status' => $this->faker->randomElement($status),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date()
        ];
    }
}
