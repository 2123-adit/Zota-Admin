<?php
// routes/api.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\NotificationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

// Public routes
Route::get('/games', [GameController::class, 'index']);
Route::get('/games/featured', [GameController::class, 'featured']);
Route::get('/games/{id}', [GameController::class, 'show']);
Route::get('/categories', [GameController::class, 'categories']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User profile
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user', [AuthController::class, 'updateProfile']);
    Route::get('/user/balance', [AuthController::class, 'getBalance']);
    
    // Transactions
    Route::post('/purchase', [TransactionController::class, 'purchase']);
    Route::post('/topup', [TransactionController::class, 'topup']);
    Route::get('/transactions', [TransactionController::class, 'transactions']);
    Route::get('/library', [TransactionController::class, 'library']);
    Route::get('/topup/status', [TransactionController::class, 'topupStatus']);
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/check', [NotificationController::class, 'checkUpdates']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
});
