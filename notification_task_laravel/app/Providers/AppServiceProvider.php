<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Notification\NotificationRepositoryInterface;
use App\Repositories\Notification\NotificationRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
