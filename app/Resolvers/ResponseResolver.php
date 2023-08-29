<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Field;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

final class ResponseResolver implements LocalResolver
{
    public static function resolve(Field $field, array $response): string
    {
        return (string) Arr::get($response, Str::of($field->valueKey)->after('$')->toString(), '');
    }
}
