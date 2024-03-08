<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AgePolicy>
 */
class AgePolicyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ageFrom = $this->faker->numberBetween(17, 120);
        $ageTo = $ageFrom + 20;

        return [
            // Should not be used for Factory::create, only Factory::make
            'id' => $this->faker->unique()->numberBetween(0, PHP_INT_MAX),
            'age_from' => $ageFrom,
            'age_to' => $ageTo,
        ];
    }

    protected function withFaker()
    {
        return \Faker\Factory::create('en');
    }
}
