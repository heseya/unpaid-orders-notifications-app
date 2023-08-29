<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Field;

interface LocalResolver
{
    public const ESCAPE = true;

    public static function resolve(Field $field, array $response): string;
}
