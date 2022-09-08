<?php

namespace App\Console\Commands;

use App\Models\Api;
use App\Services\Contracts\ApiServiceContract;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RefreshProductsFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh products feed';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        private ApiServiceContract $apiService,
    ){
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $apis = Api::all()->each(fn (Api $api) => $this->processApi($api))->count();

        $this->info("Processed $apis apis");

        return 1;
    }

    private function processApi(Api $api): void
    {
        $url = $api->url;
        $path = $this->filePath($api);
        $this->info("[$url] Processing");

        // create / overwrite file
        $file = fopen($path, 'w');
        fwrite($file, $this->headers());
        fclose($file);

        $lastPage = 1; // Get at least once
        for ($page = 1; $page <= $lastPage; $page++) {
            $fullUrl = "/products?full&limit=250&page=${page}&public=1";
            $this->info("[$url] Getting page ${page} of {$lastPage}");

            $response = $this->apiService->get($api, $fullUrl);
            $lastPage = $response->json('meta.last_page');

            // append data
            $file = fopen($path, 'a');

            foreach ($response->json('data') as $product) {
                if ($product['cover'] === null) {
                    continue;
                }

                fwrite($file, $this->product(
                    $product,
                    'https://api.***REMOVED***.pl/products',
                    'Ksiazki.pl',
                    $response->json('meta.currency.symbol'),
                ));
            }

            fclose($file);
        }
    }

    private function filePath(Api $api): string
    {
        return storage_path('app/' . $api->getKey() . '-products.csv');
    }

    private function headers(): string
    {
        return implode(',', [
            'id',
            'gtin13',
            'title',
            'description',
            'availability',
            'condition',
            'price',
            'sale_price',
            'link',
            'image_link',
            'additional_image_link',
            'brand',
            'google_product_category',
            'shipping',
            'ships_from_country',
        ]) . "\n";
    }

    private function product(array $product, string $storeFrontUrl, string $storeName, string $currency): string
    {
        $attributes = Collection::make($product['attributes']);
        $description = Str::of($product['description_html'])
            ->replace([',', "\n", '"', "'"], ' ')
            ->stripTags();

        return implode(',', [
            $product['id'],
            Arr::get($attributes->firstWhere('slug', 'ean'), 'selected_options.0.name', ''),
            "\"{$product['name']}\"",
            $description,
            $product['available'] ? 'in stock' : 'out of stock',
            'new',
            "{$product['price_min_initial']} {$currency}",
            "{$product['price_min']} {$currency}",
            "$storeFrontUrl/{$product['slug']}",
            Arr::get($product, 'cover.url', ''),
            Arr::get($product, 'gallery.1.url', ''),
            $storeName,
            $product['google_product_category'] ?? '',
            'PL',
            'PL',
        ]) . "\n";
    }
}
