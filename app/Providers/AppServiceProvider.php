<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\FacilityCategoryPolicy;
use App\Policies\LocationPolicy;
use App\Models\FacilityCategory;
use App\Models\Location;

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
        // Register policies
        Gate::policy(FacilityCategory::class, FacilityCategoryPolicy::class);
        Gate::policy(Location::class, LocationPolicy::class);

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
