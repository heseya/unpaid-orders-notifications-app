<?php

namespace App\Services;

use App\Exceptions\FileNotFoundException;
use App\Models\Feed;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileService implements Contracts\FileServiceContract
{
    public function get(Feed $feed): StreamedResponse
    {
        if (!Storage::exists($this->path($feed))) {
            throw new FileNotFoundException('Feed not found.');
        }

        return Storage::download($this->path($feed));
    }

    private function path(Feed $feed): string
    {
        return $feed->getKey();
    }
}
