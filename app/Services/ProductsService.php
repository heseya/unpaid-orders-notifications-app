<?php

namespace App\Services;

use App\Dtos\ProductsExportDto;
use App\Exports\ProductsExport;
use App\Models\Api;
use App\Services\Contracts\ApiServiceContract;
use App\Services\Contracts\ProductsServiceContract;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

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

    public function exportProducts(ProductsExportDto $dto)
    {
        $api = Api::where('url', $dto->getApi())->firstOrFail();

        $products = $this->getAll($api);

        $setting = $api->settings()->firstOrFail();

        return Excel::download(new ProductsExport($products, $setting->store_front_url), 'products.' . $dto->getFormat());
    }
}
