<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fromDate = $this->faker->dateTime();
        $toDate = (clone $fromDate)->modify('+1 week');

        return [
            // Should not be used for Factory::create, only Factory::make
            'id' => $this->faker->unique()->numberBetween(0, PHP_INT_MAX),
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'cost' => $this->faker->numberBetween(10, 1000),
            'car_id' => $this->faker->unique()->numberBetween(0, PHP_INT_MAX),
        ];
    }


    protected function withFaker()
    {
        return \Faker\Factory::create('en');
    }
}
