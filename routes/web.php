<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\GameController;
use App\Http\Controllers\Web\TopupController;
use App\Http\Controllers\Web\TransactionController;
use App\Http\Controllers\Auth\AdminAuthController;

// Redirect root to admin login
Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Login routes (accessible when not authenticated)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });
    
    // Logout route (accessible when authenticated)
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// Admin Panel Routes (Protected by AdminAuth middleware)
Route::middleware(['admin.auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::post('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{id}/adjust-balance', [UserController::class, 'adjustBalance'])->name('adjust-balance');
    });

    // Games Management
    Route::prefix('games')->name('games.')->group(function () {
        Route::get('/', [GameController::class, 'index'])->name('index');
        Route::get('/create', [GameController::class, 'create'])->name('create');
        Route::post('/', [GameController::class, 'store'])->name('store');
        Route::get('/{id}', [GameController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [GameController::class, 'edit'])->name('edit');
        Route::put('/{id}', [GameController::class, 'update'])->name('update');
        Route::delete('/{id}', [GameController::class, 'destroy'])->name('destroy');
    });

    // Top-up Management
    Route::prefix('topups')->name('topups.')->group(function () {
        Route::get('/', [TopupController::class, 'index'])->name('index');
        Route::get('/{id}', [TopupController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [TopupController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [TopupController::class, 'reject'])->name('reject');
    });

    // Transactions Monitoring
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/{id}', [TransactionController::class, 'show'])->name('show');
    });
});