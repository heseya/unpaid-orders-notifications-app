<?php

namespace App\Http\Controllers;

use App\Dtos\ProductsExportDto;
use App\Http\Requests\ProductsExportRequest;
use App\Services\Contracts\ProductsServiceContract;
use App\Traits\ReportAvailable;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductsController extends Controller
{
    use ReportAvailable;

    public function __construct(
        private readonly ProductsServiceContract $productsService,
    ) {
    }

    public function show(ProductsExportRequest $request): StreamedResponse
    {
        return $this->productsService->exportProducts(
            ProductsExportDto::fromFormRequest($request),
            true,
        );
    }

    public function showPrivate(ProductsExportRequest $request): StreamedResponse
    {
        return $this->productsService->exportProducts(
            ProductsExportDto::fromFormRequest($request),
            false,
        );
    }
}
