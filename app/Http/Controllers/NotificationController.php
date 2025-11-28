<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->paginate(20);

        $unreadCount = auth()->user()->unreadNotificationsCount();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Get notifications for dropdown (AJAX).
     */
    public function getLatest()
    {
        $notifications = auth()->user()
            ->notifications()
            ->limit(10)
            ->get();

        $unreadCount = auth()->user()->unreadNotificationsCount();

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $notification->markAsRead();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'unreadCount' => auth()->user()->unreadNotificationsCount(),
            ]);
        }

        if ($notification->url) {
            return redirect($notification->url);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        auth()->user()
            ->notifications()
            ->unread()
            ->update(['is_read' => true]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read.',
                'unreadCount' => 0,
            ]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a notification.
     */
    public function delete($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $notification->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted.',
                'unreadCount' => auth()->user()->unreadNotificationsCount(),
            ]);
        }

        return back()->with('success', 'Notification deleted.');
    }

    /**
     * Delete all notifications.
     */
    public function deleteAll()
    {
        auth()->user()->notifications()->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications deleted.',
                'unreadCount' => 0,
            ]);
        }

        return back()->with('success', 'All notifications deleted.');
    }
}
