<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Field;

interface LocalResolver
{
    public static function resolve(Field $field, array $response): string;
}
