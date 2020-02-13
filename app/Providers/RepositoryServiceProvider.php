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
            \App\Repositories\Interfaces\LocationRepositoryInterface::class, 
            \App\Repositories\LocationRepository::class
        );
        
        $this->app->bind(
            \App\Repositories\Interfaces\UserRepositoryInterface::class, 
            \App\Repositories\UserRepository::class
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
