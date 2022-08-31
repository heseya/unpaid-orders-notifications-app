<?php

namespace App\Services;

use App\Dtos\ProductsExportDto;
use App\Exceptions\SettingNotFoundException;
use App\Exports\ProductsExport;
use App\Models\Api;
use App\Services\Contracts\ApiServiceContract;
use App\Services\Contracts\ProductsServiceContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductsService implements ProductsServiceContract
{
    public function __construct(
        private ApiServiceContract $apiService,
    ) {
    }

    public function exportProducts(ProductsExportDto $dto, bool $public = true): StreamedResponse
    {
        /** @var Api $api */
        $api = Api::query()->where('url', $dto->getApi())->firstOrFail();
        $path = $this->path($api, $dto, $public);

        if (!Storage::exists($path)) {
            Log::info('File not found for ' . $api->url);

            $this->reloadProducts($api, $dto, $public);
        }

        return Storage::download($path);
    }

    public function reloadProducts(Api $api, ProductsExportDto $dto, bool $public = true): void
    {
        $setting = $api->settings()->first();

        if ($setting === null) {
            throw new SettingNotFoundException('Storefront URL is not configured');
        }

        $params = ($public ? '&public=1' : '') . $dto->getParamsToUrl();
        $products = $this->productsWithCoverAndDescriptions($this->getAll($api, $params));

        Excel::store(
            new ProductsExport($products, $setting->store_front_url, $api->name),
            $this->path($api, $dto, $public),
        );
    }

    private function path(Api $api, ProductsExportDto $dto, bool $public = true): string
    {
        return $api->getKey() . '-' . ($public ? 'products.' : 'products-private.') . $dto->getFormat();
    }

    private function getAll(Api $api, string $params = ''): Collection
    {
        return $this->apiService->getAll($api, 'products', "&full${params}", true);
    }

    private function productsWithCoverAndDescriptions(Collection $products): Collection
    {
        return $products->filter(
            fn ($product) => $product['cover'] !== null && $product['description_short'] !== null,
        );
    }
}
