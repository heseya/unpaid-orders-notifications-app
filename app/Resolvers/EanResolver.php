<?php

namespace App\Resolvers;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class EanResolver implements LocalResolver
{
    public static function resolve(array $response): string
    {
        $attribute = Collection::make($response['attributes'] ?? [])->firstWhere('slug', 'ean');

        return Arr::get($attribute, 'selected_options.0.name', '');
    }
}
