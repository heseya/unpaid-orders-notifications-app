<?php

namespace App\Services;

use App\Dtos\OrdersExportDto;
use App\Exports\OrdersExport;
use App\Models\Api;
use App\Services\Contracts\ApiServiceContract;
use App\Services\Contracts\OrdersServiceContract;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrdersService implements OrdersServiceContract
{
    public function __construct(
        private ApiServiceContract $apiService,
    ) {
    }

    public function exportOrders(OrdersExportDto $dto): BinaryFileResponse
    {
        $api = Api::where('url', $dto->getApi())->firstOrFail();

        $orders = $this->getOrders($api, $dto->getParamsToUrl());

        return Excel::download(new OrdersExport($orders), 'orders.' . $dto->getFormat());
    }

    private function getOrders(Api $api, string $params): Collection
    {
        return $this->apiService->getAll($api, 'orders', $params);
    }
}
