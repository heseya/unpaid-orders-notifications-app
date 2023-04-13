<?php

namespace App\Services\Contracts;

use App\Models\Feed;

interface VariableServiceContract
{
    public function resolve(Feed $feed): array;
}
