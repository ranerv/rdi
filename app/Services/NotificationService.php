<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Spatie\Permission\Models\Role;

class NotificationService
{
    public function notify(User $user, string $type, string $message): void
    {
        Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'message' => $message,
        ]);
    }

    public function notifyRole(string $role, string $type, string $message): void
    {
        $users = User::whereHas('roles', fn($q) => $q->where('name', $role))->get();

        foreach ($users as $user) {
            $this->notify($user, $type, $message);
        }
    }

    public function notifyMultiple(array $userIds, string $type, string $message): void
    {
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                $this->notify($user, $type, $message);
            }
        }
    }
}
