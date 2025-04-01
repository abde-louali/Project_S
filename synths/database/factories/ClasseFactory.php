<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classe>
 */
class ClasseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'code_class' => fake()->numberBetween(100, 199),
            'filier_name' => fake()->name(),
            'cin' => fake()->numberBetween(1000, 100000),
            's_fname' => fake()->firstName(),
            's_lname' => fake()->lastName(),
            'age' => fake()->numberBetween(17, 28)
        ];
    }
}
