<?php

namespace App\Services;

use App\Dtos\ProductsExportDto;
use App\Exceptions\ProductsNotFoundException;
use App\Models\Api;
use App\Services\Contracts\ProductsServiceContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

readonly class ProductsService implements ProductsServiceContract
{
    public function exportProducts(ProductsExportDto $dto, bool $public): StreamedResponse
    {
        /** @var Api $api */
        $api = Api::query()->where('url', $dto->getApi())->firstOrFail();
        $path = $this->path($api, $dto, $public);

        if (!Storage::exists($path)) {
            throw new ProductsNotFoundException('Products feed not found for this api');
        }

        return Storage::download($path);
    }

    public function getMedia(array $product): array
    {
        $cover = null;
        $additionalImage = null;

        foreach (Arr::get($product, 'gallery', []) as $media) {
            if (Arr::get($media, 'type') !== 'photo') {
                continue;
            }

            if ($cover === null) {
                $cover = Arr::get($media, 'url');
            }

            $additionalImage = Arr::get($media, 'url');
            break;
        }

        return [
            $cover,
            $additionalImage,
        ];
    }

    private function path(Api $api, ProductsExportDto $dto, bool $public = true): string
    {
        return $api->getKey() . '-' . ($public ? 'products.' : 'products-private.') . $dto->getFormat();
    }
}
