<?php

namespace App\Services;

use App\Services\Contracts\InfoServiceContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;

class InfoService implements InfoServiceContract
{
    public function index(): JsonResponse
    {
        return Response::json([
            'name' => Config::get('app.name'),
            'author' => Config::get('app.author'),
            'version' => '1.0.0',
            'api_version' => '^2.0.0',
            'description' => Config::get('app.description'),
            'microfrontend_url' => Config::get('app.microfrontend'),
            'icon' => Config::get('app.icon'),
            'licence_required' => false,
            'required_permissions' => $this->getRequiredPermissions()->flatten(),
            'internal_permissions' => Config::get('permissions.internal'),
        ]);
    }

    public function getRequiredPermissions(): Collection
    {
        $reports = Config::get('export.reports');
        $permissions = Config::get('permissions.required');
        $result = Collection::make();

        foreach ($reports as $report) {
            $result = $result->merge($permissions[$report]);
        }

        return $result->unique();
    }
}