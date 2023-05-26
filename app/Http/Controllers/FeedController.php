<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Dtos\FeedDto;
use App\Http\Requests\FeedStoreRequest;
use App\Http\Requests\FeedUpdateRequest;
use App\Http\Resources\FeedResource;
use App\Models\Feed;
use App\Services\Contracts\FeedServiceContract;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;

class FeedController extends Controller
{
    public function __construct(
        private readonly FeedServiceContract $feedService,
    ) {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return FeedResource::collection(
            $this->feedService->get($request->user()->api),
        );
    }

    public function show(Request $request, Feed $feed): FeedResource
    {
        $this->feedService->checkFeedOwner($feed, $request->user()->api);

        return FeedResource::make($feed);
    }

    public function store(FeedStoreRequest $request): JsonResource
    {
        return FeedResource::make($this->feedService->create(
            FeedDto::fromRequest($request),
            $request->user()->api,
        ));
    }

    public function update(FeedUpdateRequest $request, Feed $feed): JsonResource
    {
        return FeedResource::make($this->feedService->update(
            FeedDto::fromRequest($request),
            $feed,
            $request->user()->api,
        ));
    }

    public function destroy(Request $request, Feed $feed): HttpResponse
    {
        $this->feedService->delete($feed, $request->user()->api);

        return Response::noContent();
    }
}
