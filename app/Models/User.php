<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'balance',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function userGames()
    {
        return $this->hasMany(UserGame::class);
    }

    public function games()
    {
        return $this->belongsToMany(Game::class, 'user_games')
                   ->withPivot('purchased_at', 'transaction_id')
                   ->withTimestamps();
    }

    public function topupRequests()
    {
        return $this->hasMany(TopupRequest::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    public function hasGame($gameId)
    {
        return $this->games()->where('game_id', $gameId)->exists();
    }

    public function addBalance($amount)
    {
        $this->increment('balance', $amount);
    }

    public function deductBalance($amount)
    {
        if ($this->balance >= $amount) {
            $this->decrement('balance', $amount);
            return true;
        }
        return false;
    }
}