<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        protected $orders,
    ) {
    }

    public function collection(): Collection
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'id',
            'code',
            'email',
            'summary',
            'shipping_price',
            'summary_paid',
            'paid',
            'created_at',
            'status',
            'delivery_address.name',
            'delivery_address.address',
            'delivery_address.zip',
            'delivery_address.city',
            'delivery_address.country_name',
            'delivery_address.phone',
            'shipping_method',
        ];
    }

    public function map($row): array
    {
        return [
            $row['id'],
            $row['code'],
            $row['email'],
            "{$row['summary']} {$row['currency']}",
            "{$row['shipping_price']} {$row['currency']}",
            "{$row['summary_paid']} {$row['currency']}",
            $row['paid'],
            $row['created_at'],
            $row['status']['name'],
            $row['delivery_address']['name'],
            $row['delivery_address']['address'],
            $row['delivery_address']['zip'],
            $row['delivery_address']['city'],
            $row['delivery_address']['country_name'],
            $row['delivery_address']['phone'],
            $row['shipping_method']['name'],
        ];
    }
}
