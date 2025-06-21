<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;
use App\Models\Category;
use Illuminate\Support\Str;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        $actionCat = Category::where('slug', 'action')->first();
        $adventureCat = Category::where('slug', 'adventure')->first();
        $rpgCat = Category::where('slug', 'rpg')->first();
        $strategyCat = Category::where('slug', 'strategy')->first();
        $simulationCat = Category::where('slug', 'simulation')->first();
        $sportsCat = Category::where('slug', 'sports')->first();
        $puzzleCat = Category::where('slug', 'puzzle')->first();
        $horrorCat = Category::where('slug', 'horror')->first();

        $games = [
            // Action Games
            [
                'name' => 'Cyber Wars 2077',
                'slug' => 'cyber-wars-2077',
                'description' => 'Futuristic action game set in a cyberpunk world with intense combat and stunning graphics.',
                'price' => 299000,
                'category_id' => $actionCat->id,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Shadow Hunter',
                'slug' => 'shadow-hunter',
                'description' => 'Stealth action game where you play as a ninja in medieval Japan.',
                'price' => 199000,
                'category_id' => $actionCat->id,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Space Marine Elite',
                'slug' => 'space-marine-elite',
                'description' => 'Sci-fi action shooter with epic space battles and alien enemies.',
                'price' => 249000,
                'category_id' => $actionCat->id,
                'is_featured' => false,
                'is_active' => true,
            ],

            // Adventure Games
            [
                'name' => 'Lost Kingdom',
                'slug' => 'lost-kingdom',
                'description' => 'Epic adventure through mystical lands filled with magic and wonder.',
                'price' => 179000,
                'category_id' => $adventureCat->id,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Treasure Island Quest',
                'slug' => 'treasure-island-quest',
                'description' => 'Pirate adventure game with treasure hunting and naval battles.',
                'price' => 149000,
                'category_id' => $adventureCat->id,
                'is_featured' => false,
                'is_active' => true,
            ],

            // RPG Games
            [
                'name' => 'Dragon Age: Legends',
                'slug' => 'dragon-age-legends',
                'description' => 'Fantasy RPG with deep character customization and branching storylines.',
                'price' => 349000,
                'category_id' => $rpgCat->id,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Mystic Realm Online',
                'slug' => 'mystic-realm-online',
                'description' => 'MMORPG with massive world exploration and guild systems.',
                'price' => 199000,
                'category_id' => $rpgCat->id,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Post-Apocalypse Survivor',
                'slug' => 'post-apocalypse-survivor',
                'description' => 'Survival RPG in a post-nuclear wasteland.',
                'price' => 229000,
                'category_id' => $rpgCat->id,
                'is_featured' => false,
                'is_active' => true,
            ],

            // Strategy Games
            [
                'name' => 'Empire Builder',
                'slug' => 'empire-builder',
                'description' => 'Build and manage your civilization from ancient times to the modern era.',
                'price' => 159000,
                'category_id' => $strategyCat->id,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'War Commander',
                'slug' => 'war-commander',
                'description' => 'Real-time strategy game with modern military units and tactics.',
                'price' => 189000,
                'category_id' => $strategyCat->id,
                'is_featured' => false,
                'is_active' => true,
            ],

            // Simulation Games
            [
                'name' => 'City Builder Pro',
                'slug' => 'city-builder-pro',
                'description' => 'Create and manage the ultimate metropolis with realistic simulation.',
                'price' => 139000,
                'category_id' => $simulationCat->id,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Farm Life Simulator',
                'slug' => 'farm-life-simulator',
                'description' => 'Experience the peaceful life of a farmer with seasonal farming.',
                'price' => 99000,
                'category_id' => $simulationCat->id,
                'is_featured' => false,
                'is_active' => true,
            ],

            // Sports Games
            [
                'name' => 'Football Manager 2025',
                'slug' => 'football-manager-2025',
                'description' => 'Manage your favorite football team to victory in this realistic simulation.',
                'price' => 219000,
                'category_id' => $sportsCat->id,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Racing Champions',
                'slug' => 'racing-champions',
                'description' => 'High-speed racing game with customizable cars and tracks.',
                'price' => 169000,
                'category_id' => $sportsCat->id,
                'is_featured' => false,
                'is_active' => true,
            ],

            // Puzzle Games
            [
                'name' => 'Mind Bender',
                'slug' => 'mind-bender',
                'description' => 'Challenging puzzle game that will test your logical thinking.',
                'price' => 79000,
                'category_id' => $puzzleCat->id,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Crystal Quest',
                'slug' => 'crystal-quest',
                'description' => 'Match-3 puzzle adventure with magical elements.',
                'price' => 59000,
                'category_id' => $puzzleCat->id,
                'is_featured' => false,
                'is_active' => true,
            ],

            // Horror Games
            [
                'name' => 'Nightmare Manor',
                'slug' => 'nightmare-manor',
                'description' => 'Psychological horror game set in a haunted mansion.',
                'price' => 199000,
                'category_id' => $horrorCat->id,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Dead City',
                'slug' => 'dead-city',
                'description' => 'Zombie survival horror in an abandoned metropolitan city.',
                'price' => 179000,
                'category_id' => $horrorCat->id,
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        foreach ($games as $game) {
            Game::create($game);
        }
    }
}