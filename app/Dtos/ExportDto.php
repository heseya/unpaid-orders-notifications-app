<?php

namespace App\Dtos;

use App\Http\Requests\ExportRequest;
use Heseya\Dto\Dto;
use Illuminate\Support\Collection;

abstract class ExportDto extends Dto
{
    protected string $api;
    protected string $format;
    protected Collection $params;

    abstract public static function fromFormRequest(ExportRequest $request): self;

    public function getApi(): string
    {
        return $this->api;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function getParams(): Collection
    {
        return $this->params;
    }

    public function getParamsToUrl(): string
    {
        $result = '';

        $this->params->each(function ($item, $key) use (&$result) {
            $result .= "&${key}=${item}";
        });

        return $result;
    }
}
