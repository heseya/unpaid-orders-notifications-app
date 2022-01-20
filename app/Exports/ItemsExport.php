<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ItemsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        protected $items,
    ) {
    }

    public function collection(): Collection
    {
        return $this->items;
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'sku',
            'quantity',
        ];
    }

    public function map($row): array
    {
        return [
            $row['id'],
            $row['name'],
            $row['sku'],
            "{$row['quantity']}",
        ];
    }
}
