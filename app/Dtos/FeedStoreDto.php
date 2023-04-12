<?php

namespace App\Dtos;

use App\Http\Requests\FeedStoreRequest;
use Heseya\Dto\Dto;

final class FeedStoreDto extends Dto
{
    public readonly string $name;
    public readonly string $query;
    public readonly array $fields;

    public static function fromRequest(FeedStoreRequest $request): self
    {
        return new self(
            name: $request->input('name'),
            query: $request->input('query'),
            fields: $request->input('fields'),
        );
    }
}
