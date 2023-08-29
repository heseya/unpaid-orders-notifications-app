<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\InstallRequest;
use App\Models\Api;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Throwable;

final class InstallationController extends Controller
{
    public function install(InstallRequest $request): JsonResponse
    {
        $storeUrl = $request->input('api_url');

        if (Str::endsWith($storeUrl, '/')) {
            $storeUrl = Str::substr($storeUrl, 0, Str::length($storeUrl) - 1);
        }

        $token = $request->input('integration_token');

        try {
            $response = Http::withToken($token)->get("{$storeUrl}/auth/profile");
        } catch (Throwable) {
            throw new Exception('Failed to connect to the API');
        }

        if ($response->failed()) {
            throw new Exception('Failed to verify assigned permissions');
        }

        if ($response->json('data.url') === null) {
            throw new Exception('Integration token validation failed');
        }

        $permissions = $response->json('data.permissions');
        $requiredPermissions = Collection::make(Config::get('heseya.required_permissions'));

        if ($requiredPermissions->diff($permissions)->isNotEmpty()) {
            throw new Exception('App doesn\'t have all required permissions');
        }

        do {
            $uninstallToken = Str::random(128);
        } while (Api::where('uninstall_token', $uninstallToken)->exists());

        Api::create([
            'url' => $request->input('api_url'),
            'name' => $request->input('api_name'),
            'version' => $request->input('api_version'),
            'licence_key' => $request->input('licence_key'),
            'integration_token' => $token,
            'refresh_token' => $request->input('refresh_token'),
            'uninstall_token' => $uninstallToken,
        ]);

        return Response::json([
            'uninstall_token' => $uninstallToken,
        ]);
    }

    public function uninstall(Request $request): JsonResponse
    {
        $uninstallToken = $request->input('uninstall_token');
        $api = Api::where('uninstall_token', $uninstallToken)->firstOrFail();

        $api->delete();

        return Response::json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
