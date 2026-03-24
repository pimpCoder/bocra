<?php

namespace App\Http\Controllers\Api;

use App\Models\Notification;
use App\Models\User;
use App\Services\NotificationService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(protected NotificationService $notificationService) {}

    /**
     * Get all notifications for the authenticated user.
     */
    public function index(Request $request)
    {
        $query = Notification::where('user_id', auth('sanctum')->id())
                             ->orderBy('created_at', 'desc');

        // Optional filter: unread only
        if ($request->boolean('unread')) {
            $query->unread();
        }

        // Optional filter: by type
        if ($request->has('type')) {
            $query->ofType($request->type);
        }

        $notifications = $query->paginate(20);

        return response()->json($notifications, 200);
    }

    /**
     * Get unread notification count — for the bell icon badge.
     */
    public function unreadCount()
    {
        $count = Notification::where('user_id', auth('sanctum')->id())
                             ->unread()
                             ->count();

        return response()->json(['unread_count' => $count], 200);
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead(string $id)
    {
        $notification = Notification::where('id', $id)
                                    ->where('user_id', auth('sanctum')->id()) // security: own notifications only
                                    ->firstOrFail();

        $notification->update(['is_read' => true]);

        return response()->json(['success' => true, 'message' => 'Marked as read.'], 200);
    }

    /**
     * Mark ALL notifications as read.
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', auth('sanctum')->id())
                    ->unread()
                    ->update(['is_read' => true]);

        return response()->json(['success' => true, 'message' => 'All notifications marked as read.'], 200);
    }

    /**
     * Delete a single notification.
     */
    public function destroy(string $id)
    {
        Notification::where('id', $id)
                    ->where('user_id', auth('sanctum')->id())
                    ->firstOrFail()
                    ->delete();

        return response()->json(null, 204);
    }

    /**
     * Admin: Broadcast an alert to all users or a specific role.
     */
    public function broadcast(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500',
            'role'    => 'nullable|in:citizen,licensee,admin',
        ]);

        $query = User::query();

        if (!empty($validated['role'])) {
            $query->where('role', $validated['role']);
        }

        $userIds = $query->pluck('id')->toArray();

        $this->notificationService->broadcastAlert($userIds, $validated['message']);

        return response()->json([
            'success'    => true,
            'message'    => 'Alert broadcast successfully.',
            'recipients' => count($userIds),
        ], 201);
    }
}