<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $query = Game::with('category');

        // Fitur pencarian berdasarkan nama game
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
            // Jika 'all', tidak ada filter yang diterapkan
        }

        // Filter berdasarkan featured
        if ($request->filled('featured')) {
            if ($request->featured === 'yes') {
                $query->where('is_featured', true);
            } elseif ($request->featured === 'no') {
                $query->where('is_featured', false);
            }
        }

        // Filter berdasarkan harga
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['created_at', 'name', 'price', 'transactions_count'];
        if (in_array($sortBy, $allowedSorts)) {
            if ($sortBy === 'transactions_count') {
                // Untuk sorting berdasarkan jumlah transaksi, perlu special handling
                $query->withCount(['transactions' => function($q) {
                    $q->where('status', 'completed');
                }])->orderBy('transactions_count', $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Jika tidak sorting berdasarkan transactions_count, tetap load count
        if ($sortBy !== 'transactions_count') {
            $query->withCount(['transactions' => function($q) {
                $q->where('status', 'completed');
            }]);
        }

        $games = $query->paginate(20);

        // Load semua kategori untuk dropdown filter
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('games.index', compact('games', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('games.create', compact('categories'));
    }

    public function store(Request $request)
    {
        Log::info('=== STORE GAME DEBUG START ===');
        Log::info('All request data:', $request->all());
        Log::info('All files:', $request->allFiles());

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'screenshots.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_featured' => 'nullable',
                'is_active' => 'nullable'
            ]);

            $game = new Game();
            $game->name = $validated['name'];
            $game->slug = Str::slug($validated['name']);
            $game->description = $validated['description'] ?? null;
            $game->price = $validated['price'];
            $game->category_id = $validated['category_id'];
            $game->is_featured = $request->has('is_featured') ? true : false;
            $game->is_active = $request->has('is_active') ? true : false;

            // Cover Image
            if ($request->hasFile('cover_image')) {
                $coverFile = $request->file('cover_image');
                if ($coverFile->isValid()) {
                    $filename = time() . '_' . Str::random(10) . '.' . $coverFile->getClientOriginalExtension();
                    $coverPath = $coverFile->storeAs('games/covers', $filename, 'public');
                    $game->cover_image = $coverPath;
                }
            }

            // Screenshots
            $screenshots = [];
            if ($request->hasFile('screenshots')) {
                foreach ($request->file('screenshots') as $index => $screenshot) {
                    if ($screenshot->isValid()) {
                        $filename = time() . '_' . $index . '_' . Str::random(10) . '.' . $screenshot->getClientOriginalExtension();
                        $path = $screenshot->storeAs('games/screenshots', $filename, 'public');
                        $screenshots[] = $path;
                    }
                }
            }

            // Simpan screenshots dalam bentuk JSON
            if (!empty($screenshots)) {
                $game->screenshots = json_encode($screenshots);
            }

            $game->save();

            Log::info('Game saved successfully with ID:', ['id' => $game->id]);

            return redirect()->route('games.index')->with('success', 'Game berhasil dibuat');
        } catch (\Exception $e) {
            Log::error('Error storing game:', ['error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'Gagal membuat game: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $game = Game::with(['category', 'transactions.user'])
                   ->withCount(['transactions' => function($q) {
                       $q->where('status', 'completed');
                   }])
                   ->findOrFail($id);

        return view('games.show', compact('game'));
    }

    public function edit($id)
    {
        $game = Game::findOrFail($id);
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('games.edit', compact('game', 'categories'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'screenshots.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_featured' => 'nullable|in:0,1,true,false,on',
                'is_active' => 'nullable|in:0,1,true,false,on'
            ]);

            $game = Game::findOrFail($id);
            $game->name = $validated['name'];
            $game->slug = Str::slug($validated['name']);
            $game->description = $validated['description'] ?? null;
            $game->price = $validated['price'];
            $game->category_id = $validated['category_id'];
            $game->is_featured = $request->boolean('is_featured', false);
            $game->is_active = $request->boolean('is_active', true);

            if ($request->hasFile('cover_image')) {
                $coverFile = $request->file('cover_image');
                if ($coverFile->isValid()) {
                    if ($game->cover_image && Storage::disk('public')->exists($game->cover_image)) {
                        Storage::disk('public')->delete($game->cover_image);
                    }

                    $filename = time() . '_' . Str::random(10) . '.' . $coverFile->getClientOriginalExtension();
                    $coverPath = $coverFile->storeAs('games/covers', $filename, 'public');
                    $game->cover_image = $coverPath;
                }
            }

            if ($request->hasFile('screenshots')) {
                if ($game->screenshots) {
                    foreach (json_decode($game->screenshots, true) as $screenshot) {
                        if (Storage::disk('public')->exists($screenshot)) {
                            Storage::disk('public')->delete($screenshot);
                        }
                    }
                }

                $screenshots = [];
                foreach ($request->file('screenshots') as $index => $screenshot) {
                    if ($screenshot->isValid()) {
                        $filename = time() . '_' . $index . '_' . Str::random(10) . '.' . $screenshot->getClientOriginalExtension();
                        $path = $screenshot->storeAs('games/screenshots', $filename, 'public');
                        $screenshots[] = $path;
                    }
                }

                $game->screenshots = json_encode($screenshots);
            }

            $game->save();

            return redirect()->route('games.index')->with('success', 'Game berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating game:', ['error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['error' => 'Gagal memperbarui game: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $game = Game::findOrFail($id);

            // Cek apakah game memiliki transaksi
            $transactionCount = $game->transactions()->count();
            if ($transactionCount > 0) {
                return back()->withErrors(['error' => 'Tidak dapat menghapus game yang sudah memiliki transaksi']);
            }

            // Hapus file cover image
            if ($game->cover_image && Storage::disk('public')->exists($game->cover_image)) {
                Storage::disk('public')->delete($game->cover_image);
            }

            // Hapus file screenshots
            if ($game->screenshots) {
                foreach (json_decode($game->screenshots, true) as $screenshot) {
                    if (Storage::disk('public')->exists($screenshot)) {
                        Storage::disk('public')->delete($screenshot);
                    }
                }
            }

            $game->delete();

            return redirect()->route('games.index')->with('success', 'Game berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting game:', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Gagal menghapus game: ' . $e->getMessage()]);
        }
    }
}