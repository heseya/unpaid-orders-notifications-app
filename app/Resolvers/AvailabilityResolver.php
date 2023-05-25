<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Field;
use Illuminate\Support\Arr;

class AvailabilityResolver implements LocalResolver
{
    public static function resolve(Field $field, array $response): string
    {
        return Arr::get($response, 'available') ? 'in stock' : 'out of stock';
    }
}
