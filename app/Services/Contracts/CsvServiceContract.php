<?php

namespace App\Services\Contracts;

use App\Models\Api;
use Illuminate\Support\Collection;

interface CsvServiceContract
{
    public function productsToCsv(Collection $products, Api $api): string;
}
