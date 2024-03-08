<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Should not be used for Factory::create, only Factory::make
            'id' => $this->faker->unique()->numberBetween(0, PHP_INT_MAX),
            'model' => $this->faker->company(),
            'cost' => $this->faker->numberBetween(10, 1000),
            'registration' => $this->faker->text(7),
        ];
    }

    protected function withFaker()
    {
        return \Faker\Factory::create('en');
    }
}
