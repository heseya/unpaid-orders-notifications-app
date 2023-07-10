<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\ApiService;
use App\Services\Contracts\ApiServiceContract;
use App\Services\Contracts\FeedServiceContract;
use App\Services\Contracts\RefreshServiceContract;
use App\Services\Contracts\VariableServiceContract;
use App\Services\FeedService;
use App\Services\RefreshService;
use App\Services\VariableService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private const CONTRACTS = [
        ApiServiceContract::class => ApiService::class,
        FeedServiceContract::class => FeedService::class,
        RefreshServiceContract::class => RefreshService::class,
        VariableServiceContract::class => VariableService::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        foreach (self::CONTRACTS as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }

        /*
         * Local register of ide helper.
         * Needs to be full path.
         */
        if ($this->app->isLocal()) {
            $this->app->register('\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
        }
    }
}
