<?php

namespace App\Resolvers;

use App\Models\Feed;
use App\Services\Contracts\ApiServiceContract;
use Illuminate\Support\Facades\App;

class ShippingPriceResolver implements GlobalResolver
{
    public static function resolve(Feed $feed): string
    {
        $apiService = App::make(ApiServiceContract::class);

        $response = $apiService->get($feed->api, '/shipping-methods');
        $shippingMethods = $response->json('data');

        $minShippingPrice = array_reduce(
            $shippingMethods,
            fn ($carry, $item) => $carry === null || $item['price'] < $carry ? $item['price'] : $carry,
        ) ?? 0;

        return "PL:{$minShippingPrice} PLN";
    }
}
