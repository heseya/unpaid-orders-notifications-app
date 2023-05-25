<?php

declare(strict_types=1);

namespace App\Services;

use App\Dtos\FeedDto;
use App\Exceptions\ApiAuthorizationException;
use App\Models\Api;
use App\Models\Feed;
use App\Services\Contracts\FeedServiceContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Config;

final readonly class FeedService implements FeedServiceContract
{
    public function get(Api $api): LengthAwarePaginator
    {
        return $api->feeds()->paginate(Config::get('pagination.per_page'));
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

    /**
     * @throws ApiAuthorizationException
     */
    public function checkFeedOwner(Feed $feed, Api $api): void
    {
        if ($feed->api_id !== $api->getKey()) {
            throw new ApiAuthorizationException();
        }
    }
}
