<?php

namespace App\Services\Contracts;

use Illuminate\Support\Collection;

interface ConfigServiceContract
{
    public function getConfigs(bool $with_values): Collection;
}
