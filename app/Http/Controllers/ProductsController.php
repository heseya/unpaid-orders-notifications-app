<?php

namespace App\Http\Controllers;

use App\Dtos\ProductsExportDto;
use App\Http\Requests\ProductsExportRequest;
use App\Services\Contracts\ProductsServiceContract;
use App\Traits\ReportAvailable;

class ProductsController extends Controller
{
    use ReportAvailable;

    public function __construct(
        private ProductsServiceContract $productsService,
    ) {
    }

    public function show(ProductsExportRequest $request)
    {
        $this->reportAvailable('products');
        return $this->productsService->exportProducts(ProductsExportDto::fromFormRequest($request));
    }
}
