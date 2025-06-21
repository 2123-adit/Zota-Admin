<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->withCount(['transactions', 'userGames'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(20);

        return view('users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with(['transactions.game', 'userGames.game', 'topupRequests'])
                   ->findOrFail($id);

        return view('users.show', compact('user'));
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'suspended';
        
        return redirect()->back()->with('success', "User {$status} successfully");
    }

    public function adjustBalance(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:add,subtract',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $user = User::findOrFail($id);

        DB::beginTransaction();
        try {
            if ($request->type === 'add') {
                $user->addBalance($request->amount);
                $message = "Saldo Anda telah ditambahkan sebesar Rp " . number_format($request->amount, 0, ',', '.');
            } else {
                if ($user->balance < $request->amount) {
                    return redirect()->back()->with('error', 'Insufficient balance');
                }
                $user->deductBalance($request->amount);
                $message = "Saldo Anda telah dikurangi sebesar Rp " . number_format($request->amount, 0, ',', '.');
            }

            // Create notification
            Notification::create([
                'user_id' => $user->id,
                'type' => 'balance_adjustment',
                'title' => 'Penyesuaian Saldo',
                'message' => $message . ($request->notes ? " Catatan: {$request->notes}" : ''),
                'data' => [
                    'type' => $request->type,
                    'amount' => $request->amount,
                    'notes' => $request->notes
                ]
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Balance adjusted successfully');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to adjust balance');
        }
    }
}