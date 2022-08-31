<?php

namespace App\Services\Contracts;

use App\Dtos\ProductsExportDto;
use App\Models\Api;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface ProductsServiceContract
{
    public function exportProducts(ProductsExportDto $dto): StreamedResponse;

    public function reloadProducts(Api $api, ProductsExportDto $dto, bool $public = true): void;
}
