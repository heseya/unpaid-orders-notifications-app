<?php

namespace App\Http\Controllers;

use App\Models\Api;
use App\Services\Contracts\ApiServiceContract;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;

class ProductsController extends Controller
{
    public function __construct(private ApiServiceContract $apiService)
    {
    }

    public function show(Request $request)
    {
        $csv = 'id,title,description,availability,condition,price,link,image_link,brand';

        $api = Api::where('url', $request->input('api'))->firstOrFail();

        $products = Collection::make([]);
        $page = 0;
        do {
            $page++;
            $response = $this->apiService->get($api, "/products?limit=500&full&page=$page");
            $products = $products->concat($response->json('data'));

            $lastPage = $response->json('meta.last_page');
        } while ($page < $lastPage);

        $currency = $response->json('meta.currency.symbol');

        $products = $products->map(
            fn ($product) => implode(",", [
                $product['id'],
                $product['name'],
                $product['meta_description'],
                $product['available'] ? 'in stock' : 'out of stock',
                'new',
                $product['price'] . " $currency",
                $api->settings->products_url . $product['slug'],
                $product['cover']['url'] ?? 'https://example.com',
                'Brak',
            ]),
        )->unique();

        return Response::make(implode(PHP_EOL, [$csv, ...$products]));
    }
}
