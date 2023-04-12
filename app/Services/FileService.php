<?php

namespace App\Services;

use App\Exceptions\FileNotFoundException;
use App\Models\Feed;
use App\Services\Contracts\FileServiceContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

final readonly class FileService implements FileServiceContract
{
    public function get(Feed $feed): StreamedResponse
    {
        if (!Storage::exists($feed->path())) {
            throw new FileNotFoundException('Feed not found.');
        }

        return Storage::download($feed->path());
    }

    public function buildHeaders(Feed $feed): array
    {
        return array_keys($feed->fields);
    }

    public function buildCell(Feed $feed, array $response): array
    {
        $cells = [];

        foreach ($feed->fields as $field) {
            $cells[] = Str::of(Arr::get($response, $field))
                ->replace([',', "\n", '"', "'"], ' ')
                ->wrap('"', '"')
                ->toString();
        }

        return $cells;
    }
}
