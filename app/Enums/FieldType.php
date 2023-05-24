<?php

declare(strict_types=1);

namespace App\Enums;

enum FieldType
{
    case STANDARD;
    case VAR_GLOBAL;
    case VAR_LOCAL;
}
