<?php

namespace App\Console\Commands;

use App\Models\Feed;
use App\Services\Contracts\RefreshServiceContract;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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

        $this->info("Starting...");
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

            $processedCounter++;
            $this->info("Processed {$processedCounter}/{$totalCount} feeds.");
        }

        $this->info('Processing ended.');

        return 0;
    }

    private function product(
        array $product,
        string $storeFrontUrl,
        string $storeName,
        string $currency,
        float $shippingPrice,
        string $productType,
        string $customLabel,
    ): string {
        $attributes = Collection::make($product['attributes'] ?? []);
        $description = Str::of($product['description_html'])
            ->replace([',', "\n", '"', "'"], ' ')
            ->stripTags();

        //        [$cover, $additionalImage] = $this->productsService->getMedia($product);

        return implode(',', [
            $product['id'],
            Arr::get($attributes->firstWhere('slug', 'ean'), 'selected_options.0.name', ''),
            "\"{$product['name']}\"",
            $description,
            $product['available'] ? 'in stock' : 'out of stock',
            'new',
            ($product['price_min_initial'] ?? $product['price_min']) . " {$currency}",
            "{$product['price_min']} {$currency}",
            $storeFrontUrl . (Str::endsWith($storeFrontUrl, '/') ? '' : '/') . $product['slug'],
            $cover ?? '',
            $additionalImage ?? '',
            $storeName,
            $product['google_product_category'] ?? '',
            "PL:{$shippingPrice} {$currency}",
            "\"{$productType}\"",
            "\"{$customLabel}\"",
        ]) . "\n";
    }
}
