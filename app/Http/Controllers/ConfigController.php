<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class ConfigController extends Controller
{
    public function show(): JsonResponse
    {
        return Response::json([]);
    }

    public function store(): \Illuminate\Http\Response
    {
        return Response::noContent();
    }
}
