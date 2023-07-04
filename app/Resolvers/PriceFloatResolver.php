<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Field;
use Illuminate\Support\Arr;

class PriceFloatResolver implements LocalResolver
{
    public static function resolve(Field $field, array $response): string
    {
        return number_format(Arr::get(
            $response,
            'price_min_initial',
            Arr::get($response, 'price_min', 0),
        ), 2, '.', '');
    }
}
