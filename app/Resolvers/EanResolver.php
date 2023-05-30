<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Field;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class EanResolver implements LocalResolver
{
    public static function resolve(Field $field, array $response): string
    {
        $attribute = Collection::make(Arr::get($response, 'attributes', []))
            ->firstWhere('name', 'EAN');

        return (string) Arr::get($attribute, 'selected_options.0.name', '');
    }
}
