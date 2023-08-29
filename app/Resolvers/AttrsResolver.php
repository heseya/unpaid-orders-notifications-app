<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Field;
use Illuminate\Support\Arr;

final class AttrsResolver implements LocalResolver
{
    public const ESCAPE = false;

    public static function resolve(Field $field, array $response): string
    {
        $attributes = '';

        foreach (Arr::get($response, 'attributes', []) as $attr) {
            $value = Arr::get($attr, 'selected_options.0.name');

            $attributes .= "<a name=\"{$attr['name']}\"><![CDATA[{$value}]]></a>";
        }

        return $attributes;
    }
}
