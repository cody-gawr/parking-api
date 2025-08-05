<?php

namespace App\Repositories;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Database\Eloquent\Collection;

class NotificationRepository
{
    public function forUser(int $userId): Collection
    {
        return DatabaseNotification::where('notifiable_type', \App\Models\User::class)
            ->where('notifiable_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function find(string $uuid): ?DatabaseNotification
    {
        return DatabaseNotification::find($uuid);
    }

    public function delete(string $uuid): bool
    {
        $n = $this->find($uuid);
        return $n ? $n->delete() : false;
    }
}
