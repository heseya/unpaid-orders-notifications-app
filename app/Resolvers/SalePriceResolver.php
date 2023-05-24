<?php

declare(strict_types=1);

namespace App\Resolvers;

use Illuminate\Support\Arr;

class SalePriceResolver implements LocalResolver
{
    public static function resolve(array $response): string
    {
        return Arr::get($response, 'price_min', 0) . ' PLN';
    }
}
