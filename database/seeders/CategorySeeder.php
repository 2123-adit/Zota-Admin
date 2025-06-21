<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Action',
                'slug' => 'action',
                'description' => 'High-energy games with combat and adventure',
                'is_active' => true,
            ],
            [
                'name' => 'Adventure',
                'slug' => 'adventure',
                'description' => 'Story-driven exploration games',
                'is_active' => true,
            ],
            [
                'name' => 'RPG',
                'slug' => 'rpg',
                'description' => 'Role-playing games with character development',
                'is_active' => true,
            ],
            [
                'name' => 'Strategy',
                'slug' => 'strategy',
                'description' => 'Tactical and strategic gameplay',
                'is_active' => true,
            ],
            [
                'name' => 'Simulation',
                'slug' => 'simulation',
                'description' => 'Life and world simulation games',
                'is_active' => true,
            ],
            [
                'name' => 'Sports',
                'slug' => 'sports',
                'description' => 'Sports and racing games',
                'is_active' => true,
            ],
            [
                'name' => 'Puzzle',
                'slug' => 'puzzle',
                'description' => 'Brain-teasing puzzle games',
                'is_active' => true,
            ],
            [
                'name' => 'Horror',
                'slug' => 'horror',
                'description' => 'Scary and suspenseful games',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
