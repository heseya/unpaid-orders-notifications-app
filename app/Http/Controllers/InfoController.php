<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;

class InfoController extends Controller
{
    public function index(): JsonResponse
    {
        return Response::json([
            'name' => 'Facebook/Instagram Catalog',
            'author' => 'Heseya',
            'version' => '0.1.0',
            'api_version' => '^2.0.0',
            'description' => 'Application lets you put your products on Facebook/Instagram marketplace',
            'icon' => Config::get('app.url') . '/logo.png',
            'licence_required' => false,
            'required_permissions' => Config::get('permissions.required'),
            'internal_permissions' => Config::get('permissions.internal'),
        ]);
    }
}
