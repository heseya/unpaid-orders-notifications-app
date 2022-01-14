<?php

namespace App\Dtos;

use App\Http\Requests\ProductsExportRequest;
use Heseya\Dto\Dto;

class ProductsExportDto extends Dto
{
    protected string $api;
    protected string $format;

    public static function fromFormRequest(ProductsExportRequest $request): self
    {
        return new self(
            api: $request->input('api'),
            format: $request->input('format'),
        );
    }

    public function getApi(): string
    {
        return $this->api;
    }

    public function getFormat(): string
    {
        return $this->format;
    }
}
