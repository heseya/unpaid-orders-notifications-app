<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Field;
use Illuminate\Support\Str;

class ProductUrlResolver implements LocalResolver
{
    public static function resolve(Field $field, array $response): string
    {
        return $response['slug'] ?
            Str::of($field->valueKey)->after(' ')->trim('/') . '/' . $response['slug'] : '';
    }
}
