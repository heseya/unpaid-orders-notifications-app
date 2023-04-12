<?php

namespace App\Services\Contracts;

use App\Dtos\FeedDto;
use App\Models\Api;
use App\Models\Feed;
use Illuminate\Support\Collection;

interface FeedServiceContract
{
    public function get(Api $api): Collection;

    public function create(FeedDto $dto, Api $api): Feed;

    public function update(FeedDto $dto, Feed $feed, Api $api): Feed;

    public function delete(Feed $feed, Api $api): void;
}
