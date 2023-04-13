<?php

namespace App\Resolvers;

use Illuminate\Support\Arr;

class PriceResolver implements LocalResolver
{
    public static function resolve(array $response): string
    {
        return Arr::get(
            $response,
            'price_min_initial',
            Arr::get($response, 'price_min', 0),
        ) . ' PLN';
    }
}
