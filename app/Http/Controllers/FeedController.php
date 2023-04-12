<?php

namespace App\Http\Controllers;

use App\Http\Resources\FeedResource;
use App\Services\Contracts\FeedServiceContract;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FeedController extends Controller
{
    public function __construct(
        private readonly FeedServiceContract $feedService,
    ) {
    }

    public function show(Request $request): AnonymousResourceCollection
    {
        return FeedResource::collection(
            $this->feedService->get($request->user()->api),
        );
    }
}
