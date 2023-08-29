<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Field;

interface GlobalResolver
{
    public const ESCAPE = true;

    public static function resolve(Field $field): string;
}
