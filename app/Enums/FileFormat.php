<?php

declare(strict_types=1);

namespace App\Enums;

use App\Services\FileServiceCSV;
use App\Services\FileServiceXML;

enum FileFormat: string
{
    case CSV = 'csv';
    case XML = 'xml';

    public function service(): string
    {
        return match ($this) {
            self::CSV => FileServiceCSV::class,
            self::XML => FileServiceXML::class,
        };
    }
}
