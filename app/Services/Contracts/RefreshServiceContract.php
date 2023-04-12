<?php

namespace App\Services\Contracts;

use App\Models\Feed;

interface RefreshServiceContract
{
    public function refreshFeed(Feed $feed): void;
}
