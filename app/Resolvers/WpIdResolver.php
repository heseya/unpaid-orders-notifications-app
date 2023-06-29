<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Field;
use Illuminate\Support\Arr;

class WpIdResolver implements LocalResolver
{
    public static function resolve(Field $field, array $response): string
    {
        $meta = Arr::get($response, 'metadata_private');

        return $meta['wp_id'] ?? Arr::get($response, 'id', '');
    }
}
