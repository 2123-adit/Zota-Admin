<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\TopupRequest;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopupController extends Controller
{
    public function index(Request $request)
    {
        $query = TopupRequest::with('user');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $topups = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('topups.index', compact('topups'));
    }

    public function show($id)
    {
        $topup = TopupRequest::with('user')->findOrFail($id);
        return view('topups.show', compact('topup'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'notes' => 'nullable|string'
        ]);

        $topup = TopupRequest::findOrFail($id);

        if ($topup->status !== 'pending') {
            return redirect()->back()->with('error', 'Top up request already processed');
        }

        DB::beginTransaction();
        try {
            // Update topup status
            $topup->update([
                'status' => 'approved',
                'notes' => $request->notes,
                'processed_at' => now()
            ]);

            // Add balance to user
            $topup->user->addBalance($topup->amount);

            // Create notification
            Notification::create([
                'user_id' => $topup->user_id,
                'type' => 'topup_approved',
                'title' => 'Top Up Disetujui!',
                'message' => "Permintaan top up sebesar Rp " . number_format($topup->amount, 0, ',', '.') . " telah disetujui dan saldo Anda telah ditambahkan.",
                'data' => [
                    'topup_id' => $topup->id,
                    'amount' => $topup->amount,
                    'notes' => $request->notes
                ]
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Top up approved successfully');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to approve top up');
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string'
        ]);

        $topup = TopupRequest::findOrFail($id);

        if ($topup->status !== 'pending') {
            return redirect()->back()->with('error', 'Top up request already processed');
        }

        $topup->update([
            'status' => 'rejected',
            'notes' => $request->notes,
            'processed_at' => now()
        ]);

        // Create notification
        Notification::create([
            'user_id' => $topup->user_id,
            'type' => 'topup_rejected',
            'title' => 'Top Up Ditolak',
            'message' => "Permintaan top up sebesar Rp " . number_format($topup->amount, 0, ',', '.') . " ditolak. Alasan: {$request->notes}",
            'data' => [
                'topup_id' => $topup->id,
                'amount' => $topup->amount,
                'notes' => $request->notes
            ]
        ]);

        return redirect()->back()->with('success', 'Top up rejected successfully');
    }
}