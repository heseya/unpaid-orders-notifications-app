<?php

namespace App\Http\Controllers;

use App\Dtos\ItemsExportDto;
use App\Http\Requests\ItemsExportRequest;
use App\Services\Contracts\ItemsServiceContract;
use App\Traits\ReportAvailable;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ItemsController extends Controller
{
    use ReportAvailable;

    public function __construct(
        private ItemsServiceContract $itemsService,
    ) {
    }

    public function show(ItemsExportRequest $request): BinaryFileResponse
    {
        $this->reportAvailable('items');
        return $this->itemsService->exportItems(ItemsExportDto::fromFormRequest($request));
    }
}
