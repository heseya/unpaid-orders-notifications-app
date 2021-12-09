<?php

namespace App\Services\Contracts;

use App\Models\Api;
use Illuminate\Support\Collection;

interface ProductsServiceContract
{
    public function getAll(Api $api): Collection;
}
