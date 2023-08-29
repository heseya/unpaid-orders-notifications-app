<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Field;

final class StringResolver implements GlobalResolver
{
    public static function resolve(Field $field): string
    {
        return $field->valueKey;
    }
}
