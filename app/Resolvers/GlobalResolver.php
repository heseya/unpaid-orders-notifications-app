<?php

namespace App\Resolvers;

use App\Models\Feed;

interface GlobalResolver
{
    public static function resolve(Feed $feed): string;
}
