<?php

namespace App\Services\Contracts;

use App\Models\Feed;
use App\Models\Field;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface FileServiceContract
{
    public function get(Feed $feed): StreamedResponse;

    public function buildHeaders(Feed $feed): array;

    /**
     * @param Field[] $fields
     */
    public function buildCell(array $fields, array $response): array;
}
