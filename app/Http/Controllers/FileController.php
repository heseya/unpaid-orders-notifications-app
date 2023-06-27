<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\AuthType;
use App\Exceptions\BasicAuthException;
use App\Exceptions\FileNotFoundException;
use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function show(Feed $feed, Request $request): StreamedResponse
    {
        if (
            $feed->auth === AuthType::BASIC &&
            $request->getUser() !== $feed->username &&
            $request->getPassword() !== $feed->password
        ) {
            throw new BasicAuthException();
        }

        if (!Storage::exists($feed->path())) {
            throw new FileNotFoundException('Feed not found.');
        }

        return Storage::download($feed->path());
    }
}
