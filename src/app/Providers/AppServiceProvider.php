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
        // Repositories
        $this->app->bind(
            \App\Repositories\Interfaces\EventRepositoryInterface::class,
            \App\Repositories\EventRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\EventAttendeeRepositoryInterface::class,
            \App\Repositories\EventAttendeeRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\PhotoRepositoryInterface::class,
            \App\Repositories\PhotoRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\RideRepositoryInterface::class,
            \App\Repositories\RideRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\RideRequestRepositoryInterface::class,
            \App\Repositories\RideRequestRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\ReportRepositoryInterface::class,
            \App\Repositories\ReportRepository::class

        );

        // Services
        $this->app->bind(
            \App\Services\Interfaces\EventServiceInterface::class,
            \App\Services\EventService::class
        );

        $this->app->bind(
            \App\Services\Interfaces\EventAttendeeServiceInterface::class,
            \App\Services\EventAttendeeService::class
        );

        $this->app->bind(
            \App\Services\Interfaces\PhotoServiceInterface::class,
            \App\Services\PhotoService::class
        );

        $this->app->bind(
            \App\Services\Interfaces\RideServiceInterface::class,
            \App\Services\RideService::class
        );

        $this->app->bind(
            \App\Services\Interfaces\RideRequestServiceInterface::class,
            \App\Services\RideRequestService::class
        );

        $this->app->bind(
            \App\Services\Interfaces\UserServiceInterface::class,
            \App\Services\UserService::class
        );

        $this->app->bind(
            \App\Services\Interfaces\ReportServiceInterface::class,
            \App\Services\ReportService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
