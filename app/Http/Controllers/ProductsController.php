<?php

namespace App\Http\Controllers;

use App\Models\Api;
use App\Services\Contracts\CsvServiceContract;
use App\Services\Contracts\ProductsServiceContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ProductsController extends Controller
{
    public function __construct(
        private CsvServiceContract $csvService,
        private ProductsServiceContract $productsService,
    ) {
    }

    public function show(Request $request)
    {
        $api = Api::where('url', $request->input('api'))->firstOrFail();

        $products = $this->productsService->getAll($api);

        return Response::make(
            $this->csvService->productsToCsv($products, $api),
        );
    }
}
