<?php

namespace App\Resolvers;

interface LocalResolver
{
    public static function resolve(array $response): string;
}
