<?php

declare(strict_types=1);

namespace App\Enums;

enum AuthType: string
{
    case NO = 'no';
    case BASIC = 'basic';
}
