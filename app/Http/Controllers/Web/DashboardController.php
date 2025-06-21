<?php
// app/Http/Controllers/Web/DashboardController.php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Game;
use App\Models\Transaction;
use App\Models\TopupRequest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_games' => Game::where('is_active', true)->count(),
            'pending_topups' => TopupRequest::where('status', 'pending')->count(),
            'today_sales' => Transaction::where('type', 'purchase')
                                      ->where('status', 'completed')
                                      ->whereDate('created_at', today())
                                      ->sum('amount'),
            'total_sales' => Transaction::where('type', 'purchase')
                                      ->where('status', 'completed')
                                      ->sum('amount'),
            'total_balance' => User::sum('balance'),
        ];

        $recentTransactions = Transaction::with(['user', 'game'])
                                       ->latest()
                                       ->limit(10)
                                       ->get();

        $topGames = Game::withCount(['transactions' => function($query) {
                         $query->where('status', 'completed');
                     }])
                     ->orderBy('transactions_count', 'desc')
                     ->limit(5)
                     ->get();

        return view('dashboard.index', compact('stats', 'recentTransactions', 'topGames'));
    }
}