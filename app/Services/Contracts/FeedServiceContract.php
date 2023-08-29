<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Dtos\FeedDto;
use App\Models\Api;
use App\Models\Feed;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface FeedServiceContract
{
    public function get(Api $api): LengthAwarePaginator;

    public function create(FeedDto $dto, Api $api): Feed;

    public function update(FeedDto $dto, Feed $feed, Api $api): Feed;

    public function delete(Feed $feed, Api $api): void;

    public function checkFeedOwner(Feed $feed, Api $api): void;
}
