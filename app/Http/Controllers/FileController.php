<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use App\Services\Contracts\FileServiceContract;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function __construct(
        private readonly FileServiceContract $fileService,
    ) {
    }

    public function show(Feed $feed): StreamedResponse
    {
        return $this->fileService->get($feed);
    }
}
