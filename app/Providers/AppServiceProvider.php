<?php

namespace App\Providers;

use App\Services\ApiService;
use App\Services\ConfigService;
use App\Services\Contracts\ApiServiceContract;
use App\Services\Contracts\ConfigServiceContract;
use App\Services\Contracts\InfoServiceContract;
use App\Services\Contracts\ItemsServiceContract;
use App\Services\Contracts\OrdersServiceContract;
use App\Services\Contracts\ProductsServiceContract;
use App\Services\InfoService;
use App\Services\ItemsService;
use App\Services\OrdersService;
use App\Services\ProductsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    const CONTRACTS = [
        ApiServiceContract::class => ApiService::class,
        ProductsServiceContract::class => ProductsService::class,
        InfoServiceContract::class => InfoService::class,
        ConfigServiceContract::class => ConfigService::class,
        OrdersServiceContract::class => OrdersService::class,
        ItemsServiceContract::class => ItemsService::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        foreach (self::CONTRACTS as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }

        /**
         * Local register of ide helper.
         * Needs to be full path.
         */
        if ($this->app->isLocal()) {
            $this->app->register('\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
        }
    }
}
