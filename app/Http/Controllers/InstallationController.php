<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class InstallationController extends Controller
{
    public function install(): JsonResponse
    {
        return Response::json([
            "uninstall_token" => "4v2f3c98765984vcb52310x9zn28346",
        ]);
    }

    public function uninstall(): JsonResponse
    {
        return Response::json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
