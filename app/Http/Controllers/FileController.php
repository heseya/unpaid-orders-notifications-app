<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\AuthType;
use App\Exceptions\BasicAuthException;
use App\Models\Feed;
use App\Services\Contracts\FileServiceContract;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function __construct(
        private readonly FileServiceContract $fileService,
    ) {
    }

    public function show(Feed $feed, Request $request): StreamedResponse
    {
        if (
            $feed->auth === AuthType::BASIC &&
            $request->getUser() !== $feed->username &&
            $request->getPassword() !== $feed->password
        ) {
            throw new BasicAuthException();
        }

        return $this->fileService->get($feed);
    }
}
