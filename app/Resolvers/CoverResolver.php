<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Field;
use Illuminate\Support\Arr;

class CoverResolver implements LocalResolver
{
    public static function resolve(Field $field, array $response): string
    {
        foreach (Arr::get($response, 'gallery', []) as $media) {
            if (Arr::get($media, 'type') !== 'photo') {
                continue;
            }

            return Arr::get($media, 'url', '');
        }

        return '';
    }
}
