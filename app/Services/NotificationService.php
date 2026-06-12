<?php

namespace App\Services;

use App\Repositories\NotificationRepository;

class NotificationService
{
    public function __construct(
        protected NotificationRepository $notificationRepository,
    ) {}

    /**
     * Send an internal notification to a user.
     */
    public function notify(int $userId, string $title, string $message): void
    {
        $this->notificationRepository->create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'is_read' => false,
        ]);
    }

    /**
     * Get the unread notification count for a user.
     */
    public function getUnreadCount(int $userId): int
    {
        return $this->notificationRepository->countUnreadByUser($userId);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(int $id): bool
    {
        return $this->notificationRepository->markAsRead($id);
    }

    /**
     * Mark all notifications as read for a user.
     */
    public function markAllAsRead(int $userId): int
    {
        return $this->notificationRepository->markAllAsReadForUser($userId);
    }
}
