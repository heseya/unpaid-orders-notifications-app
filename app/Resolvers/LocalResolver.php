<?php

declare(strict_types=1);

namespace App\Resolvers;

interface LocalResolver
{
    public static function resolve(array $response): string;
}
