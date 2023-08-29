<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Field;
use Illuminate\Support\Arr;

final class ImgsResolver implements LocalResolver
{
    public const ESCAPE = false;

    public static function resolve(Field $field, array $response): string
    {
        $images = '';
        $first = true;

        foreach (Arr::get($response, 'gallery', []) as $media) {
            if ($media['type'] !== 'photo') {
                continue;
            }

            if ($first) {
                $images .= "<main url=\"{$media['url']}\"/>";
                $first = false;
            } else {
                $images .= "<i url=\"{$media['url']}\"/>";
            }
        }

        return $images;
    }
}
