<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
    public function index()
    {
        // جلب الإشعارات مع التصفية والتقسيم
        $notifications = auth()->user()->notifications()->paginate(10);

        return view('dashboard.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }

        return redirect()->back();
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back();
    }

    public function deleteSelected(Request $request)
    {
        $request->validate([
            'selected_notifications' => 'required|array',
        ]);

        // الحصول على إشعارات المستخدم الحالي
        $user = auth()->user();
        $user->notifications()->whereIn('id', $request->selected_notifications)->delete();

        return redirect()->back()->with('success', 'Selected notifications deleted successfully.');
    }

    public function handleActions(Request $request)
    {
        $request->validate([
            'selected_notifications' => 'required|array',
        ]);

        $user = auth()->user();
        $action = $request->input('action');

        if ($action == 'delete') {
            $user->notifications()->whereIn('id', $request->selected_notifications)->delete();
            return redirect()->back()->with('success', 'Selected notifications deleted successfully.');
        }

        if ($action == 'mark-as-read') {
            $user->notifications()->whereIn('id', $request->selected_notifications)->update(['read_at' => now()]);
            return redirect()->back()->with('success', 'Selected notifications marked as read successfully.');
        }

        return redirect()->back()->with('error', 'Invalid action.');
    }

    public function delete($id)
{
    $notification = auth()->user()->notifications()->find($id);
    if ($notification) {
        $notification->delete();
    }

    return redirect()->back()->with('success', 'Notification deleted successfully.');
}

}
