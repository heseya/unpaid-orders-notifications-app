<?php

namespace App\Services;

use App\Models\Api;
use App\Services\Contracts\ApiServiceContract;
use App\Services\Contracts\ProductsServiceContract;
use Illuminate\Support\Collection;

class ProductsService implements ProductsServiceContract
{
    public function __construct(
        private ApiServiceContract $apiService,
    ) {
    }

    public function getAll(Api $api): Collection
    {
        $products = Collection::make([]);

        $lastPage = 1; // Get products at least once
        for ($page = 1; $page <= $lastPage; $page++) {
            $response = $this->apiService->get($api, "/products?limit=500&full&page=$page");
            $products = $products->concat($response->json('data'));

            $lastPage = $response->json('meta.last_page');
        }

        $currency = $response->json('meta.currency.symbol');

        return $products->map(fn ($product) => $product + ['currency' => $currency]);
    }
}
