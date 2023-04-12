<?php

namespace App\Providers;

use App\Services\ApiService;
use App\Services\ConfigService;
use App\Services\Contracts\ApiServiceContract;
use App\Services\Contracts\ConfigServiceContract;
use App\Services\Contracts\FeedServiceContract;
use App\Services\Contracts\ProductsServiceContract;
use App\Services\FeedService;
use App\Services\ProductsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private const CONTRACTS = [
        FeedServiceContract::class => FeedService::class,
        ApiServiceContract::class => ApiService::class,
        ConfigServiceContract::class => ConfigService::class,
        ProductsServiceContract::class => ProductsService::class,
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
