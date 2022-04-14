<?php

namespace App\Services\Contracts;

use App\Dtos\ProductsExportDto;
use App\Models\Api;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

interface ProductsServiceContract
{
    public function getAll(Api $api, string $params): Collection;

    public function exportProducts(ProductsExportDto $dto): BinaryFileResponse;
}
