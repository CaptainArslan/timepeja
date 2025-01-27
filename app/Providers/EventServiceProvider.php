<?php

namespace App\Providers;

use App\Models\Driver;
use App\Models\Manager;
use App\Models\Vehicle;
use App\Models\Schedule;
use App\Models\Organization;
use App\Observers\DriverObserver;
use App\Observers\ManagerObserver;
use App\Observers\VehicleObserver;
use App\Observers\ScheduleObserver;
use App\Events\FcmNotificationEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Observers\OrganizationObserver;
use App\Listeners\FcmNotificationListener;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        FcmNotificationEvent::class => [
            FcmNotificationListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // Schedule::observe(ScheduleObserver::class);
        // Driver::observe(DriverObserver::class);
        // Vehicle::observe(VehicleObserver::class);
        // Manager::observe(ManagerObserver::class);
        // Organization::observe(OrganizationObserver::class);
    }
}
