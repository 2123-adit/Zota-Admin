<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'cover_image',
        'screenshots',
        'category_id',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'screenshots' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Add these attributes to JSON output
    protected $appends = [
        'cover_image_url',
        'screenshots_url',
        'formatted_price'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function userGames()
    {
        return $this->hasMany(UserGame::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_games')
                    ->withPivot('purchased_at', 'transaction_id')
                    ->withTimestamps();
    }

    /**
     * Get full URL for cover image with improved error handling
     */
    public function getCoverImageUrlAttribute()
    {
        try {
            if (!$this->cover_image) {
                Log::debug("Game {$this->id} ({$this->name}): No cover image set");
                return null; // Return null instead of default image
            }

            // If already a full URL, return as is
            if (str_starts_with($this->cover_image, 'http')) {
                Log::debug("Game {$this->id} ({$this->name}): Cover image is already full URL: {$this->cover_image}");
                return $this->cover_image;
            }

            // Clean up the path
            $imagePath = ltrim($this->cover_image, '/');
            
            // Check if file exists in storage
            if (!Storage::disk('public')->exists($imagePath)) {
                Log::warning("Game {$this->id} ({$this->name}): Cover image file not found in storage: {$imagePath}");
                return null;
            }

            $fullUrl = asset('storage/' . $imagePath);
            Log::debug("Game {$this->id} ({$this->name}): Generated cover image URL: {$fullUrl}");
            
            return $fullUrl;
            
        } catch (\Exception $e) {
            Log::error("Error generating cover image URL for game {$this->id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get full URLs for screenshots with improved error handling
     */
    public function getScreenshotsUrlAttribute()
    {
        try {
            if (!$this->screenshots || !is_array($this->screenshots) || empty($this->screenshots)) {
                Log::debug("Game {$this->id} ({$this->name}): No screenshots available");
                return [];
            }

            $screenshotUrls = [];
            
            foreach ($this->screenshots as $index => $screenshot) {
                if (!$screenshot) {
                    Log::debug("Game {$this->id} ({$this->name}): Screenshot {$index} is empty");
                    continue;
                }

                // If already a full URL, add as is
                if (str_starts_with($screenshot, 'http')) {
                    $screenshotUrls[] = $screenshot;
                    Log::debug("Game {$this->id} ({$this->name}): Screenshot {$index} is already full URL: {$screenshot}");
                    continue;
                }

                // Clean up the path
                $screenshotPath = ltrim($screenshot, '/');
                
                // Check if file exists in storage
                if (!Storage::disk('public')->exists($screenshotPath)) {
                    Log::warning("Game {$this->id} ({$this->name}): Screenshot file not found in storage: {$screenshotPath}");
                    continue;
                }

                $fullUrl = asset('storage/' . $screenshotPath);
                $screenshotUrls[] = $fullUrl;
                Log::debug("Game {$this->id} ({$this->name}): Generated screenshot {$index} URL: {$fullUrl}");
            }

            Log::debug("Game {$this->id} ({$this->name}): Total valid screenshots: " . count($screenshotUrls));
            return $screenshotUrls;
            
        } catch (\Exception $e) {
            Log::error("Error generating screenshot URLs for game {$this->id}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Scope for active games only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured games only
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('name', 'like', '%' . $term . '%')
              ->orWhere('description', 'like', '%' . $term . '%');
        });
    }

    /**
     * Check if game is owned by user
     */
    public function isOwnedBy($userId)
    {
        return $this->userGames()->where('user_id', $userId)->exists();
    }

    /**
     * Get sales count
     */
    public function getSalesCountAttribute()
    {
        return $this->userGames()->count();
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Create slug when creating a new game
        static::creating(function ($game) {
            if (!$game->slug) {
                $game->slug = \Str::slug($game->name);
            }
        });

        // Update slug when updating name
        static::updating(function ($game) {
            if ($game->isDirty('name')) {
                $game->slug = \Str::slug($game->name);
            }
        });
    }

    /**
     * Override toArray to ensure image URLs are included
     */
    public function toArray()
    {
        $array = parent::toArray();
        
        // Ensure image URLs are included
        $array['cover_image_url'] = $this->cover_image_url;
        $array['screenshots_url'] = $this->screenshots_url;
        $array['formatted_price'] = $this->formatted_price;
        
        // Debug output
        Log::debug("Game {$this->id} toArray:", [
            'name' => $this->name,
            'cover_image' => $this->cover_image,
            'cover_image_url' => $array['cover_image_url'],
            'screenshots_count' => count($array['screenshots_url'] ?? [])
        ]);
        
        return $array;
    }
}