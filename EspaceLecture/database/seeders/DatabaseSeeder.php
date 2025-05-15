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

        // Création de 10 utilisateurs normaux
        \App\Models\User::factory(10)->create();

        // Création de 5 catégories
        \App\Models\Category::factory(5)->create();

        // Création de 30 livres avec leurs relations
        \App\Models\Book::factory(30)->create();

        // Création de 100 commentaires aléatoires
        \App\Models\Review::factory(20)->create();
    }
}