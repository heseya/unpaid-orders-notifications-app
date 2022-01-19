<?php

namespace App\Services;

use App\Dtos\ProductsExportDto;
use App\Exports\ProductsExport;
use App\Models\Api;
use App\Services\Contracts\ApiServiceContract;
use App\Services\Contracts\ProductsServiceContract;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductsService implements ProductsServiceContract
{
    public function __construct(
        private ApiServiceContract $apiService,
    ) {
    }

    public function getAll(Api $api, bool $public = true): Collection
    {
        $products = Collection::make([]);
        $public = $public ? '&public=1' : '';

        $lastPage = 1; // Get products at least once
        for ($page = 1; $page <= $lastPage; $page++) {
            $response = $this->apiService->get($api, "/products?limit=500&full&page=${page}${public}");
            $products = $products->concat($response->json('data'));

            $lastPage = $response->json('meta.last_page');
        }

        $currency = $response->json('meta.currency.symbol');

        return $products->map(fn ($product) => $product + ['currency' => $currency]);
    }

    public function exportProducts(ProductsExportDto $dto, bool $public = true): BinaryFileResponse
    {
        $api = Api::where('url', $dto->getApi())->firstOrFail();

        $products = $this->productsWithCoverAndDescriptions($this->getAll($api, $public));

        $setting = $api->settings()->firstOrFail();

        return Excel::download(
            new ProductsExport($products, $setting->store_front_url),
            ($public ? 'products.' : 'products-private.') . $dto->getFormat()
        );
    }

    private function productsWithCoverAndDescriptions(Collection $products): Collection
    {
        return $products->filter(
            fn ($product) => $product['cover'] !== null && $product['description_short'] !== null,
        );
    }
}
