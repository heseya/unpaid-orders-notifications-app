<?php

namespace App\Services\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

interface InfoServiceContract
{
    public function index(): JsonResponse;

    public function getRequiredPermissions(): Collection;
}
