<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfigStoreRequest;
use App\Models\Api;
use App\Models\Settings;
use App\Services\Contracts\ConfigServiceContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class ConfigController extends Controller
{
    public function __construct(
        private ConfigServiceContract $configService,
    ) {
    }

    public function show(): JsonResponse
    {
        $with_values = false;

        if (Gate::allows('config')) {
            $with_values = true;
        }

        return Response::json($this->configService->getConfigs($with_values));
    }

    public function store(ConfigStoreRequest $request)
    {
        $payload = Auth::getTokenPayload();
        $api = Api::where('url', $payload['iss'])->firstOrFail();

        $productsUrl = $request->input('store_front_url');

        Settings::updateOrCreate(['api_id' => $api->getKey()], [
            'store_front_url' => Str::endsWith($productsUrl, '/') ? $productsUrl : "${productsUrl}/",
        ]);

        return Response::json($this->configService->getConfigs(true));
    }
}
