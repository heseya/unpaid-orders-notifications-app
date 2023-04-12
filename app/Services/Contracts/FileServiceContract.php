<?php

namespace App\Services\Contracts;

use App\Models\Feed;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface FileServiceContract
{
    public function get(Feed $feed): StreamedResponse;

    public function buildHeaders(Feed $feed): array;

    public function buildCell(Feed $feed, array $response): array;
}
