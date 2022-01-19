<?php

namespace App\Services\Contracts;

use App\Dtos\OrdersExportDto;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

interface OrdersServiceContract
{
    public function exportOrders(OrdersExportDto $dto): BinaryFileResponse;
}
