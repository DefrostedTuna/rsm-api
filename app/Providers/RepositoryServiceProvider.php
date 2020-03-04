<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Contracts\Services\AuthService::class,
            \App\Services\AuthService::class
        );

        $this->app->bind(
            \App\Contracts\Services\UserService::class, 
            \App\Services\UserService::class,
        );

        $this->app->bind(
            \App\Contracts\Services\LocationService::class, 
            \App\Services\LocationService::class,
        );
        
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
