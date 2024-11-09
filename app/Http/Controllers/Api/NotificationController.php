<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // جلب الإشعارات مع التصفية والتقسيم
        $notifications = auth()->user()->notifications()->paginate(10);

        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['message' => 'Notification marked as read successfully.']);
        }

        return response()->json(['message' => 'Notification not found.'], 404);
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json(['message' => 'All notifications marked as read successfully.']);
    }

    public function deleteSelected(Request $request)
    {
        $request->validate([
            'selected_notifications' => 'required|array',
        ]);

        // الحصول على إشعارات المستخدم الحالي
        $user = auth()->user();
        $user->notifications()->whereIn('id', $request->selected_notifications)->delete();

        return response()->json(['message' => 'Selected notifications deleted successfully.']);
    }

    public function handleActions(Request $request)
    {
        $request->validate([
            'selected_notifications' => 'required|array',
            'action' => 'required|string',
        ]);

        $user = auth()->user();
        $action = $request->input('action');

        if ($action == 'delete') {
            $user->notifications()->whereIn('id', $request->selected_notifications)->delete();
            return response()->json(['message' => 'Selected notifications deleted successfully.']);
        }

        if ($action == 'mark-as-read') {
            $user->notifications()->whereIn('id', $request->selected_notifications)->update(['read_at' => now()]);
            return response()->json(['message' => 'Selected notifications marked as read successfully.']);
        }

        return response()->json(['message' => 'Invalid action.'], 400);
    }

    public function delete($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->delete();
            return response()->json(['message' => 'Notification deleted successfully.']);
        }

        return response()->json(['message' => 'Notification not found.'], 404);
    }
}
