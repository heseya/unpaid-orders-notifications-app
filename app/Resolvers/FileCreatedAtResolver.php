<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Field;
use Illuminate\Support\Carbon;

class FileCreatedAtResolver implements GlobalResolver
{
    public static function resolve(Field $field): string
    {
        return Carbon::now()->toAtomString();
    }
}
