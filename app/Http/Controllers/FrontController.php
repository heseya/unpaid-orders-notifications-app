<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FrontController extends Controller
{
    public function show(string $file): StreamedResponse
    {
        $file = Str::of($file)->trim('/');

        return Storage::download("front/{$file}");
    }
}
