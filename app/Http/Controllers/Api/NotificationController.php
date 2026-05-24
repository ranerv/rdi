<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController
{
    public function index(Request $request): JsonResponse
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => NotificationResource::collection($notifications->items()),
            'pagination' => [
                'total' => $notifications->total(),
                'per_page' => $notifications->perPage(),
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
            ],
            'unread_count' => $request->user()->notifications()->where('read', false)->count(),
            'message' => 'Notifications retrieved',
            'status' => true,
        ]);
    }

    public function markRead(Notification $notification): JsonResponse
    {
        $this->authorize('update', $notification);

        $notification->update(['read' => true]);

        return response()->json([
            'data' => new NotificationResource($notification),
            'message' => 'Notification marked as read',
            'status' => true,
        ]);
    }

    public function markAllRead(Request $request): JsonResponse
    {
        $request->user()
            ->notifications()
            ->where('read', false)
            ->update(['read' => true]);

        return response()->json([
            'data' => null,
            'message' => 'All notifications marked as read',
            'status' => true,
        ]);
    }

    public function destroy(Notification $notification): JsonResponse
    {
        $this->authorize('delete', $notification);

        $notification->delete();

        return response()->json([
            'data' => null,
            'message' => 'Notification deleted',
            'status' => true,
        ]);
    }
}
