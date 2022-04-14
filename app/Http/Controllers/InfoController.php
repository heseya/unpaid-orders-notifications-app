<?php

namespace App\Http\Controllers;

use App\Services\Contracts\InfoServiceContract;
use Illuminate\Http\JsonResponse;

class InfoController extends Controller
{
    public function __construct(
        private InfoServiceContract $infoService,
    ) {
    }

    public function index(): JsonResponse
    {
        return $this->infoService->index();
    }
}
