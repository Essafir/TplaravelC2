<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'book_id' => \App\Models\Book::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->optional(0.7)->paragraph,
        ];
    }
}