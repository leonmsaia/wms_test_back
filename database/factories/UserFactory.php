<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre'   => $this->faker->firstName(),
            'apellido' => $this->faker->lastName(),
            'email'    => $this->faker->unique()->safeEmail(),
            'rol'      => $this->faker->randomElement(['Admin', 'User']),
            'password' => Hash::make('secret1234'),
            'remember_token' => Str::random(10),
        ];
    }
}