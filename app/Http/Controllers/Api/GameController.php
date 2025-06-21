<?php
// app/Http/Controllers/Api/GameController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    /**
     * Transform game data to include full image URLs
     */
    private function transformGame($game)
    {
        // Clone the game data to avoid modifying the original
        $gameData = $game->toArray();
        
        // Add full URLs for images
        $gameData['cover_image_url'] = $game->cover_image 
            ? asset('storage/' . $game->cover_image)
            : null;
            
        // Handle screenshots if they exist
        if ($game->screenshots) {
            $screenshots = is_string($game->screenshots) 
                ? json_decode($game->screenshots, true) 
                : $game->screenshots;
                
            if (is_array($screenshots)) {
                $gameData['screenshots_url'] = array_map(function($screenshot) {
                    return asset('storage/' . $screenshot);
                }, $screenshots);
            } else {
                $gameData['screenshots_url'] = [];
            }
        } else {
            $gameData['screenshots_url'] = [];
        }
        
        return $gameData;
    }

    /**
     * Transform a collection of games
     */
    private function transformGames($games)
    {
        return $games->map(function($game) {
            return $this->transformGame($game);
        });
    }

    public function index(Request $request)
    {
        try {
            $query = Game::with('category')->where('is_active', true);

            // Search
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%');
                });
            }

            // Filter by category
            if ($request->has('category_id') && !empty($request->category_id)) {
                $query->where('category_id', $request->category_id);
            }

            // Filter featured
            if ($request->has('featured') && $request->featured == 'true') {
                $query->where('is_featured', true);
            }

            // Order by featured first, then by created_at desc
            $query->orderBy('is_featured', 'desc')
                  ->orderBy('created_at', 'desc');

            $games = $query->paginate(12);
            
            // Transform the data to include full image URLs
            $transformedGames = $this->transformGames($games->getCollection());
            $games->setCollection($transformedGames);

            return response()->json([
                'success' => true,
                'data' => $games,
                'message' => 'Games retrieved successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching games: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch games',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $game = Game::with('category')->where('is_active', true)->find($id);

            if (!$game) {
                return response()->json([
                    'success' => false,
                    'message' => 'Game not found'
                ], 404);
            }

            // Transform the game data to include full image URLs
            $transformedGame = $this->transformGame($game);

            return response()->json([
                'success' => true,
                'data' => $transformedGame,
                'message' => 'Game retrieved successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching game: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch game',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function featured()
    {
        try {
            $games = Game::with('category')
                        ->where('is_active', true)
                        ->where('is_featured', true)
                        ->orderBy('created_at', 'desc')
                        ->limit(6)
                        ->get();

            // Transform the games to include full image URLs
            $transformedGames = $this->transformGames($games);

            return response()->json([
                'success' => true,
                'data' => $transformedGames,
                'message' => 'Featured games retrieved successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching featured games: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch featured games',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function categories()
    {
        try {
            $categories = Category::where('is_active', true)
                                 ->withCount(['activeGames'])
                                 ->orderBy('name')
                                 ->get();

            return response()->json([
                'success' => true,
                'data' => $categories,
                'message' => 'Categories retrieved successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching categories: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get games by category
     */
    public function gamesByCategory($categoryId)
    {
        try {
            $category = Category::where('is_active', true)->find($categoryId);
            
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            $games = Game::with('category')
                        ->where('is_active', true)
                        ->where('category_id', $categoryId)
                        ->orderBy('is_featured', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->paginate(12);

            // Transform the games to include full image URLs
            $transformedGames = $this->transformGames($games->getCollection());
            $games->setCollection($transformedGames);

            return response()->json([
                'success' => true,
                'data' => $games,
                'category' => $category,
                'message' => 'Games by category retrieved successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching games by category: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch games by category',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}