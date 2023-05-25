<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\FileNotFoundException;
use App\Models\Feed;
use App\Resolvers\LocalResolver;
use App\Services\Contracts\FileServiceContract;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

final readonly class FileService implements FileServiceContract
{
    /**
     * @throws FileNotFoundException
     */
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

    public function buildCell(array $fields, array $response): array
    {
        $cells = [];

        foreach ($fields as $field) {
            $value = $field->resolver instanceof LocalResolver ?
                $field->getLocalValue($response) :
                $field->getGlobalValue();

            $cells[] = Str::of($value)
                ->replace([',', "\n", '"', "'"], ' ')
                ->wrap('"', '"')
                ->toString();
        }

        return $cells;
    }
}
