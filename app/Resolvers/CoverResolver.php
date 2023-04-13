<?php

namespace App\Resolvers;

use Illuminate\Support\Arr;

class CoverResolver implements LocalResolver
{
    public static function resolve(array $response): string
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
