<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Transaction;
use App\Models\UserGame;
use App\Models\TopupRequest;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function purchase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'game_id' => 'required|exists:games,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $game = Game::find($request->game_id);

        // Check if user already owns the game
        if ($user->hasGame($game->id)) {
            return response()->json([
                'success' => false,
                'message' => 'You already own this game'
            ], 400);
        }

        // Check if user has sufficient balance
        if ($user->balance < $game->price) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance',
                'data' => [
                    'required' => $game->price,
                    'current_balance' => $user->balance,
                    'shortage' => $game->price - $user->balance
                ]
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Create transaction
            $transaction = Transaction::create([
                'transaction_id' => Transaction::generateTransactionId(),
                'user_id' => $user->id,
                'type' => 'purchase',
                'amount' => $game->price,
                'status' => 'completed',
                'game_id' => $game->id,
                'details' => [
                    'game_name' => $game->name,
                    'game_price' => $game->price
                ],
                'processed_at' => now()
            ]);

            // Deduct balance
            $user->deductBalance($game->price);

            // Add game to user's library
            UserGame::create([
                'user_id' => $user->id,
                'game_id' => $game->id,
                'transaction_id' => $transaction->id,
                'purchased_at' => now()
            ]);

            // Create notification
            Notification::create([
                'user_id' => $user->id,
                'type' => 'purchase_success',
                'title' => 'Pembelian Berhasil!',
                'message' => "Game {$game->name} berhasil dibeli dan ditambahkan ke library Anda.",
                'data' => [
                    'game_id' => $game->id,
                    'game_name' => $game->name,
                    'transaction_id' => $transaction->transaction_id
                ]
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Game purchased successfully',
                'data' => [
                    'transaction' => $transaction,
                    'game' => $game,
                    'remaining_balance' => $user->fresh()->balance
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Purchase failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function topup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:10000|max:10000000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        
        $topupRequest = TopupRequest::create([
            'request_id' => TopupRequest::generateRequestId(),
            'user_id' => $user->id,
            'amount' => $request->amount,
            'status' => 'pending',
            'notes' => $request->notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Top up request submitted successfully',
            'data' => $topupRequest
        ]);
    }

    public function transactions(Request $request)
    {
        $transactions = Transaction::where('user_id', $request->user()->id)
                                 ->with('game')
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    public function library(Request $request)
    {
        $user = $request->user();
        $games = $user->games()->with('category');

        if ($request->has('search')) {
            $games->where('name', 'like', '%' . $request->search . '%');
        }

        $games = $games->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $games
        ]);
    }

    public function topupStatus(Request $request)
    {
        $topupRequests = TopupRequest::where('user_id', $request->user()->id)
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $topupRequests
        ]);
    }
}