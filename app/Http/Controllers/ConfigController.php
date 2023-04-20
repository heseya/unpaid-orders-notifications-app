<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;

class ConfigController extends Controller
{
    public function show(): JsonResponse
    {
        return Response::json([]);
    }

    public function store(): HttpResponse
    {
        return Response::noContent();
    }
}
