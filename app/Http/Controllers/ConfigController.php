<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfigStoreRequest;
use App\Models\Api;
use App\Models\Settings;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class ConfigController extends Controller
{
    public function show(): JsonResponse
    {
        $fields = Collection::make(
            Config::get('settings.fields'),
        );

        if (Gate::allows('config')) {
            $payload = Auth::getTokenPayload();
            $api = Api::where('url', $payload['iss'])->firstOrFail();
            $settings = $api->settings;

            $fields = $fields->map(fn ($setting) => $setting + [
                'value' => match ($setting['key']) {
                    'csv_url' => Config::get('app.url') . '/products?api=' . $api->url,
                    default => $settings[$setting['key']] ?? null,
                }
            ]);
        }

        return Response::json($fields);
    }

    public function store(ConfigStoreRequest $request)
    {
        $payload = Auth::getTokenPayload();
        $api = Api::where('url', $payload['iss'])->firstOrFail();

        $productsUrl = $request->input('products_url');

        $settings = Settings::updateOrCreate(['api_id' => $api->getKey()], [
           'products_url' => Str::endsWith($productsUrl, '/') ? $productsUrl : "$productsUrl/",
        ]);

        $fields = Collection::make(
            Config::get('settings.fields'),
        )->map(fn ($setting) => $setting + [
            'value' => match ($setting['key']) {
                'csv_url' => Config::get('app.url') . '/products?api=' . $api->url,
                default => $settings[$setting['key']] ?? null,
            }
        ]);

        return Response::json($fields);
    }
}
