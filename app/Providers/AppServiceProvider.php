<?php

namespace App\Providers;

use App\Services\ApiService;
use App\Services\Contracts\ApiServiceContract;
use App\Services\Contracts\FeedServiceContract;
use App\Services\Contracts\FileServiceContract;
use App\Services\Contracts\RefreshServiceContract;
use App\Services\FeedService;
use App\Services\FileService;
use App\Services\RefreshService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private const CONTRACTS = [
        ApiServiceContract::class => ApiService::class,
        FeedServiceContract::class => FeedService::class,
        FileServiceContract::class => FileService::class,
        RefreshServiceContract::class => RefreshService::class,
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
