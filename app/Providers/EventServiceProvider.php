<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     *
     * @todo Send an email when a user is verified.
     * @todo Send an email when a password is changed.
     */
    protected $listen = [
        'App\Events\Auth\Registered' => [
            'App\Listeners\Auth\SendEmailVerificationNotification',
        ],
        'App\Events\Auth\Verified' => [
            // Listeners here...
        ],
        'App\Events\Auth\PasswordChanged' => [
            // Listeners here...
        ],
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
