<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre'   => $this->faker->firstName(),
            'apellido' => $this->faker->lastName(),
            'email'    => $this->faker->unique()->safeEmail(),
            'rol'      => $this->faker->randomElement(['Admin','User']),
            'password' => 'secret1234',
        ];
    }
}