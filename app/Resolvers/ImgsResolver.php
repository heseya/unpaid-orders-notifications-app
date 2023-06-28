<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Field;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ImgsResolver implements LocalResolver
{
    public static function resolve(Field $field, array $response): string
    {
        $images = Collection::make();
        $first = true;

        foreach (Arr::get($response, 'gallery', []) as $media) {
            if ($media['type'] !== 'photo') {
                continue;
            }

            if ($first) {
                $images->push('<main url="' . $media['url'] . '"/>');
                $first = false;
            } else {
                $images->push('<i url="' . $media['url'] . '"/>');
            }
        }

        return $images->implode('');
    }
}
