<?php

declare(strict_types=1);

namespace App\Models;

use App\Resolvers\GlobalResolver;
use App\Resolvers\LocalResolver;

final class Field
{
    private bool $isResolved = false;

    public function __construct(
        public readonly Feed $feed,
        public readonly string $key,
        public readonly string $valueKey,
        public readonly GlobalResolver|LocalResolver $resolver,
        public string $value = '',
    ) {
    }

    public function getGlobalValue(): string
    {
        if ($this->isResolved) {
            return $this->value;
        }

        $this->value = $this->resolver::resolve($this);
        $this->isResolved = true;

        return $this->value;
    }

    public function getLocalValue(array $response = []): string
    {
        return $this->resolver::resolve($this, $response);
    }
}
