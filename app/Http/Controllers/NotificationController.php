<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService,
    ) {}

    /**
     * Mark a single notification as read.
     */
    public function markAsRead(int $id): RedirectResponse
    {
        $this->notificationService->markAsRead($id);

        return redirect()->back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }

    /**
     * Mark all notifications as read for current user.
     */
    public function markAllAsRead(): RedirectResponse
    {
        $this->notificationService->markAllAsRead(auth()->id());

        return redirect()->back()->with('success', 'Semua notifikasi ditandai sebagai dibaca.');
    }
}
