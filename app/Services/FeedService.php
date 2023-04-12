<?php

namespace App\Services;

use App\Models\Api;
use App\Models\Feed;
use App\Services\Contracts\FeedServiceContract;
use Illuminate\Support\Collection;

class FeedService implements FeedServiceContract
{
    public function get(Api $api): Collection
    {
        return Feed::query()->where('api_id', $api->getKey())->get();
    }
}
