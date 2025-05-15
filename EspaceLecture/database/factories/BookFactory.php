<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name,
            'summary' => $this->faker->paragraphs(3, true),
            'pages' => $this->faker->numberBetween(50, 800),
            'published_at' => $this->faker->dateTimeBetween('-10 years', 'now'),
            'category_id' => \App\Models\Category::factory(),
            'status' => $this->faker->randomElement(['available', 'borrowed']),
            'cover' => $this->faker->optional()->imageUrl(200, 300, 'book'),
        ];
    }
}