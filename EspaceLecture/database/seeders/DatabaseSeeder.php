<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Création d'un admin par défaut
        \App\Models\User::factory()->create([
            'name' => 'Administrateur',
            'email' => 'admin@espacelecture.com',
            'role' => 'admin',
        ]);

        \App\Models\User::factory(10)->create();

        
        \App\Models\Category::factory(5)->create();

        \App\Models\Book::factory(30)->create();

        \App\Models\Review::factory(20)->create();
    }
}