<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Feed;
use App\Services\Contracts\RefreshServiceContract;
use Exception;
use Illuminate\Console\Command;

class Refresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh all feeds';

    /**
     * Create a new command instance.
     */
    public function __construct(
        private readonly RefreshServiceContract $refreshService,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $feeds = Feed::all();

        $processedCounter = 0;
        $totalCount = $feeds->count();

        $this->info('Starting...');
        $this->info("{$totalCount} feeds to process.");

        foreach ($feeds as $feed) {
            try {
                $this->info("[{$feed->api->url} - {$feed->name}] Starting...");
                $this->refreshService->refreshFeed($feed);
                $this->info("[{$feed->api->url} - {$feed->name}] Processed successfully!");
            } catch (Exception $exception) {
                $this->error("[{$feed->api->url} - {$feed->name}] {$exception->getMessage()}");
                $this->error("[{$feed->api->url} - {$feed->name}] Failed processing. Skipping!");

                if (app()->bound('sentry')) {
                    app('sentry')->captureException($exception);
                }
            }

            ++$processedCounter;
            $this->info("Processed {$processedCounter}/{$totalCount} feeds.");
        }

        $this->info('Processing ended.');

        return 0;
    }
}
