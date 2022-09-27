<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfigStoreRequest;
use App\Models\Api;
use App\Models\Settings;
use App\Services\Contracts\ConfigServiceContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    public function show(Request $request): JsonResponse
    {
        $with_values = false;
        $api_url = null;

        if (Gate::allows('configure')) {
            $with_values = true;
            $payload = Auth::getTokenPayload();
            $api_url = $payload ? $payload['iss'] : $request->header('X-Core-Url');
        }

        return Response::json($this->configService->getConfigs($with_values, $api_url));
    }

    public function store(ConfigStoreRequest $request)
    {
        $payload = Auth::getTokenPayload();
        $api_url = $payload ? $payload['iss'] : $request->header('X-Core-Url');
        $api = Api::where('url', $api_url)->firstOrFail();

        $productsUrl = $request->input('store_front_url');

        Settings::updateOrCreate(['api_id' => $api->getKey()], [
            'store_front_url' => Str::endsWith($productsUrl, '/') ? $productsUrl : "${productsUrl}/",
            'product_type_set_parent_filter' => $request->input('product_type_set_parent_filter'),
            'product_type_set_no_parent_filter' => $request->boolean('product_type_set_no_parent_filter'),
            'google_custom_label_metatag' => $request->input('google_custom_label_metatag'),
        ]);

        return Response::json($this->configService->getConfigs(true, $api_url));
    }
}
