<?php

namespace App\Http\Controllers;

use App\Dtos\OrdersExportDto;
use App\Http\Requests\OrdersExportRequest;
use App\Services\Contracts\OrdersServiceContract;
use App\Traits\ReportAvailable;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrdersController extends Controller
{
    use ReportAvailable;

    public function __construct(
        private OrdersServiceContract $ordersService,
    ) {
    }

    public function show(OrdersExportRequest $request): BinaryFileResponse
    {
        $this->reportAvailable('orders');
        return $this->ordersService->exportOrders(OrdersExportDto::fromFormRequest($request));
    }
}
