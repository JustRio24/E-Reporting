<?php

namespace App\Repositories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationRepository extends BaseRepository
{
    public function __construct(Notification $model)
    {
        parent::__construct($model);
    }

    public function getByUser(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->newQuery()
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function getUnreadByUser(int $userId): Collection
    {
        return $this->newQuery()
            ->where('user_id', $userId)
            ->unread()
            ->latest()
            ->get();
    }

    public function countUnreadByUser(int $userId): int
    {
        return $this->newQuery()
            ->where('user_id', $userId)
            ->unread()
            ->count();
    }

    public function markAsRead(int $id): bool
    {
        return $this->update($id, ['is_read' => true]);
    }

    public function markAllAsReadForUser(int $userId): int
    {
        return $this->newQuery()
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
}
