<?php

namespace App\Dtos;

use App\Http\Requests\FeedStoreRequest;
use App\Http\Requests\FeedUpdateRequest;
use Heseya\Dto\Dto;
use Heseya\Dto\Missing;

final class FeedDto extends Dto
{
    public readonly string|Missing $name;
    public readonly string|Missing $query;
    public readonly array|Missing $fields;

    public static function fromRequest(FeedStoreRequest|FeedUpdateRequest $request): self
    {
        return new self(
            name: $request->input('name', new Missing()),
            query: $request->input('query', new Missing()),
            fields: $request->input('fields', new Missing()),
        );
    }
}
