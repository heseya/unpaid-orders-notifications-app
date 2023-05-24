<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FieldType;
use App\Resolvers\GlobalResolver;
use App\Resolvers\LocalResolver;
use Illuminate\Support\Arr;

final class Field
{
    private bool $isResolved = false;

    public function __construct(
        public readonly Feed $feed,
        public readonly string $key,
        public readonly string $valueKey,
        public readonly FieldType $type,
        public readonly GlobalResolver|LocalResolver|null $resolver,
        public string $value = '',
    ) {
    }

    public function getGlobalValue(): string
    {
        if ($this->isResolved) {
            return $this->value;
        }

        $this->value = $this->resolver::resolve($this->feed);
        $this->isResolved = true;

        return $this->value;
    }

    public function getLocalValue(array $response = []): string
    {
        if ($this->resolver === null) {
            return (string) Arr::get($response, $this->valueKey, '');
        }

        return $this->resolver::resolve($response);
    }
}
