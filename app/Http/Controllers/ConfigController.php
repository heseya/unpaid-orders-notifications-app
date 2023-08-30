<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ConfigUpdateRequest;
use App\Models\Api;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;

final class ConfigController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        /** @var Api $api */
        $api = $request->user()->api;

        return Response::json([
            [
                'key' => 'name',
                'label' => 'Nazwa nadawcy maila',
                'type' => 'text',
                'default_value' => $api->name,
                'value' => $api->name,
                'required' => true,
            ],
            [
                'key' => 'payment_url',
                'label' => 'Link do płatności',
                'type' => 'text',
                'default_value' => $api->payment_url,
                'value' => $api->payment_url,
                'required' => true,
            ],
            [
                'key' => 'orders_from_days',
                'label' => 'Liczba dni po których zostanie wysłane powiadomienie',
                'type' => 'number',
                'default_value' => $api->orders_from_days,
                'value' => $api->orders_from_days,
                'required' => true,
            ],
        ]);
    }

    public function store(ConfigUpdateRequest $request): HttpResponse
    {
        /** @var Api $api */
        $api = $request->user()->api;

        $api->update([
            'name' => $request->input('name'),
            'payment_url' => $request->input('payment_url'),
            'orders_from_days' => $request->input('orders_from_days'),
        ]);

        return Response::noContent();
    }
}
