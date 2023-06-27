<?php

declare(strict_types=1);

namespace App\Enums;

enum FileFormat: string
{
    case CSV = 'csv';
    case XML = 'xml';

    public function hasHeaders(): bool
    {
        return match ($this) {
            self::CSV => true,
            self::XML => false,
        };
    }
}
