<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->id)
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    public function checkUpdates(Request $request)
    {
        $user = $request->user();
        $lastCheck = $request->input('last_check');
        
        $query = Notification::where('user_id', $user->id);
        
        if ($lastCheck) {
            $query->where('created_at', '>', $lastCheck);
        }
        
        $newNotifications = $query->orderBy('created_at', 'desc')->get();
        $unreadCount = Notification::where('user_id', $user->id)
                                 ->where('is_read', false)
                                 ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'new_notifications' => $newNotifications,
                'unread_count' => $unreadCount,
                'current_balance' => $user->balance,
                'check_time' => now()->toISOString()
            ]
        ]);
    }

    public function markAsRead($id, Request $request)
    {
        $notification = Notification::where('user_id', $request->user()->id)
                                  ->where('id', $id)
                                  ->first();

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        Notification::where('user_id', $request->user()->id)
                   ->where('is_read', false)
                   ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }
}