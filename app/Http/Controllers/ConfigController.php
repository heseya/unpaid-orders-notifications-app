<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class ConfigController extends Controller
{
    public function show(): JsonResponse
    {
        return Response::json([
            [
                "key" => "facebook_graph_token",
                "label" => "Token to Facebook's Graph API with manage_catalogue permission",
                "placeholder" => "token_string",
                "type" => "text",
                "default_value" => null,
                "required" => true,
                "options" => [],
            ]
        ]);
    }

    public function store()
    {
        return Response::json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
