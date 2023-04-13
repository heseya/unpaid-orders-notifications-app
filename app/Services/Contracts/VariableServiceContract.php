<?php

namespace App\Services\Contracts;

interface VariableServiceContract
{
    public function resolve(array $keys): array;
}
