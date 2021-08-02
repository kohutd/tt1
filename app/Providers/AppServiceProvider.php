<?php

namespace App\Providers;

use App\Repositories\ClientsRepository;
use App\Repositories\ClientsRepositoryImpl;
use App\Repositories\UsersRepository;
use App\Repositories\UsersRepositoryImpl;
use App\Services\Geocoding\GeocodingService;
use App\Services\Geocoding\GoogleGeocodingService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            UsersRepository::class,
            UsersRepositoryImpl::class
        );
        $this->app->singleton(
            ClientsRepository::class,
            ClientsRepositoryImpl::class
        );

        $this->app->singleton(
            GeocodingService::class,
            GoogleGeocodingService::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
