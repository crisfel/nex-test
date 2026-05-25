<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'status' => fake()->randomElement(['activo', 'inactivo', 'prospecto']),
            'description' => fake()->optional()->sentence(),
            'user_id' => User::factory(),
        ];
    }
}
