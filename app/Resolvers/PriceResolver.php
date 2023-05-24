<?php

declare(strict_types=1);

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
