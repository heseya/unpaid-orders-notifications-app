<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\Feed;

interface FileServiceContract
{
    public function buildHeader(Feed $feed): string;

    public function buildRow(array $fields, array $response): string;

    public function buildEnding(Feed $feed): string;
}
