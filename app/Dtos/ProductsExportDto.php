<?php

namespace App\Dtos;

use App\Http\Requests\ExportRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class ProductsExportDto extends ExportDto
{
    public static function fromFormRequest(ExportRequest $request): self
    {
        return new self(
            api: $request->input('api'),
            format: $request->input('format', Config::get('export.default_format')),
            params: Collection::make($request->except([
                'api',
                'format',
                'public',
            ])),
        );
    }
}
