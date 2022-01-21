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

    public function getAll(Api $api, string $params = ''): Collection
    {
        return $this->apiService->getAll($api, 'products', "&full${params}", true);
    }

    public function exportProducts(ProductsExportDto $dto, bool $public = true): BinaryFileResponse
    {
        $api = Api::where('url', $dto->getApi())->firstOrFail();
        $params = ($public ? '&public=1' : '') . $dto->getParamsToUrl();

        $products = $this->productsWithCoverAndDescriptions($this->getAll($api, $params));

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
