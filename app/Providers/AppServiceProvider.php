<?php

namespace App\Providers;

use App\Services\ApiService;
use App\Services\ConfigService;
use App\Services\Contracts\ApiServiceContract;
use App\Services\Contracts\ConfigServiceContract;
use App\Services\Contracts\InfoServiceContract;
use App\Services\Contracts\ProductsServiceContract;
use App\Services\InfoService;
use App\Services\ProductsService;
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
        $this->app->bind(ApiServiceContract::class, ApiService::class);
        $this->app->bind(ProductsServiceContract::class, ProductsService::class);
        $this->app->bind(InfoServiceContract::class, InfoService::class);
        $this->app->bind(ConfigServiceContract::class, ConfigService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
