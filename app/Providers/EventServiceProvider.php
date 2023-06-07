<?php

namespace App\Providers;

use App\Models\Location;
use App\Models\Organisation;
use App\Models\User;
use App\Observers\LocationObserver;
use App\Observers\OrganisationObserver;
use App\Observers\UserAdminObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * The model observers for your application.
     *
     * @var array
     */
    protected $observers = [
        Location::class => [LocationObserver::class],
        Organisation::class => [OrganisationObserver::class],
        User::class => [UserAdminObserver::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        //
    }
}
