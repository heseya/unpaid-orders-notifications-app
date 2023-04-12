<?php

namespace App\Services;

use App\Dtos\FeedDto;
use App\Exceptions\ApiAuthorizationException;
use App\Models\Api;
use App\Models\Feed;
use App\Services\Contracts\FeedServiceContract;
use Illuminate\Support\Collection;

class FeedService implements FeedServiceContract
{
    public function get(Api $api): Collection
    {
        return $api->feeds;
    }

    public function create(FeedDto $dto, Api $api): Feed
    {
        return $api->feeds()->create($dto->toArray());
    }

    public function update(FeedDto $dto, Feed $feed, Api $api): Feed
    {
        $this->checkFeedOwner($feed, $api);
        $feed->update($dto->toArray());

        return $feed;
    }

    public function delete(Feed $feed, Api $api): void
    {
        $this->checkFeedOwner($feed, $api);
        $feed->delete();
    }

    private function checkFeedOwner(Feed $feed, Api $api): void
    {
        if ($feed->api_id !== $api->getKey()) {
            throw new ApiAuthorizationException();
        }
    }
}
