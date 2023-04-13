<?php

namespace App\Models;

use App\Enums\FieldType;
use App\Resolvers\GlobalResolver;
use App\Resolvers\LocalResolver;

final class Field
{
    public function __construct(
        public readonly string $key,
        public readonly string $valueKey,
        public readonly FieldType $type,
        public readonly GlobalResolver|LocalResolver $resolver,
        public string $value = '',
    ) {
    }
}
