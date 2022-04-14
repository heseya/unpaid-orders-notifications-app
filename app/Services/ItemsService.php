<?php

namespace App\Services;

use App\Dtos\ItemsExportDto;
use App\Exports\ItemsExport;
use App\Models\Api;
use App\Services\Contracts\ApiServiceContract;
use App\Services\Contracts\ItemsServiceContract;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ItemsService implements ItemsServiceContract
{
    public function __construct(
        private ApiServiceContract $apiService,
    ) {
    }

    public function exportItems(ItemsExportDto $dto): BinaryFileResponse
    {
        $api = Api::where('url', $dto->getApi())->firstOrFail();

        $items = $this->getAll($api, $dto->getParamsToUrl());

        return Excel::download(new ItemsExport($items), 'items.' . $dto->getFormat());
    }

    private function getAll(Api $api, string $params): Collection
    {
        return $this->apiService->getAll($api, 'items', $params);
    }
}
