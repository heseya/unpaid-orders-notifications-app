<?php

namespace App\Services\Contracts;

use App\Models\Api;
use Illuminate\Support\Collection;

interface FeedServiceContract
{
    public function get(Api $api): Collection;
}
