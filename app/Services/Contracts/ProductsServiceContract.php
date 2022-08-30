<?php

namespace App\Services\Contracts;

use App\Dtos\ProductsExportDto;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface ProductsServiceContract
{
    public function exportProducts(ProductsExportDto $dto): StreamedResponse;
}
