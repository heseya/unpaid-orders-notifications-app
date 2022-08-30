<?php

namespace App\Services\Contracts;

use App\Dtos\ProductsExportDto;
use App\Models\Api;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface ProductsServiceContract
{
    public function exportProducts(ProductsExportDto $dto): StreamedResponse;
}
