<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Field;
use Illuminate\Support\Arr;

class AvailResolver implements LocalResolver
{
    public static function resolve(Field $field, array $response): string
    {
        return Arr::get($response, 'metadata.ask_for_price', false) ? '90' : // towar na zamówienie
            (
                Arr::get($response, 'available')
                ? self::toCeneoFormat(Arr::get($response, 'shipping_time'))
                : '99' // brak informacji o dostępności – status „sprawdź w sklepie”
            );
    }

    private static function toCeneoFormat(mixed $shipping_time): string
    {
        return match ($shipping_time) {
            0, 1 => '1',
            2, 3 => '3',
            4, 5, 6, 7 => '7',
            8, 9, 10, 11, 12, 13, 14 => '14',
            default => '99',
        };
    }
}
