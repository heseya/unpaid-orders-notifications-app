<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Field;
use Illuminate\Support\Arr;

class SkuResolver implements LocalResolver
{
    public static function resolve(Field $field, array $response): string
    {
        return Arr::get($response, 'items.0.sku', '');
    }
}
