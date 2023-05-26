<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Field;
use App\Services\Contracts\ApiServiceContract;
use Illuminate\Support\Facades\App;

class ShippingPriceResolver implements GlobalResolver
{
    public static function resolve(Field $field): string
    {
        $apiService = App::make(ApiServiceContract::class);

        $response = $apiService->get($field->feed->api, '/shipping-methods');
        $shippingMethods = $response->json('data');

        $minShippingPrice = array_reduce(
            $shippingMethods,
            fn ($carry, $item) => $carry === null || $item['price'] < $carry ? $item['price'] : $carry,
        ) ?? 0;

        return "PL:{$minShippingPrice} PLN";
    }
}
