<?php

namespace App\Providers;

use App\Services\ApiService;
use App\Services\Contracts\ApiServiceContract;
use App\Services\Contracts\CsvServiceContract;
use App\Services\Contracts\ProductsServiceContract;
use App\Services\CsvService;
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
        $this->app->bind(CsvServiceContract::class, CsvService::class);
        $this->app->bind(ProductsServiceContract::class, ProductsService::class);
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
