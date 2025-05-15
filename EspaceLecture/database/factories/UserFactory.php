<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Mot de passe par défaut
            'role' => $this->faker->randomElement(['user', 'admin']),
            'avatar' => $this->faker->optional()->imageUrl(100, 100, 'people'),
        ];
    }

    public function admin()
    {
        return $this->state([
            'role' => 'admin',
        ]);
    }

    public function user()
    {
        return $this->state([
            'role' => 'user',
        ]);
    }
}