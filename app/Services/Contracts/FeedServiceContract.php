<?php

namespace App\Services\Contracts;

use App\Dtos\FeedStoreDto;
use App\Models\Api;
use App\Models\Feed;
use Illuminate\Support\Collection;

interface FeedServiceContract
{
    public function get(Api $api): Collection;

    public function create(FeedStoreDto $dto, Api $api): Feed;

    public function delete(Feed $feed, Api $api): void;
}
