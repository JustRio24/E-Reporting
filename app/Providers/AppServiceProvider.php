<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            if (auth()->check()) {
                try {
                    $notificationService = app(\App\Services\NotificationService::class);
                    $notificationRepo = app(\App\Repositories\NotificationRepository::class);
                    $view->with([
                        'unreadNotificationsCount' => $notificationService->getUnreadCount(auth()->id()),
                        'unreadNotificationsList' => $notificationRepo->getUnreadByUser(auth()->id()),
                    ]);
                } catch (\Exception $e) {
                    // Prevent crash if database or tables don't exist yet during command run
                }
            }
        });
    }
}
