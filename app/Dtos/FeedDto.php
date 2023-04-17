<?php

namespace App\Dtos;

use App\Enums\AuthType;
use App\Http\Requests\FeedStoreRequest;
use App\Http\Requests\FeedUpdateRequest;
use Heseya\Dto\Dto;
use Heseya\Dto\Missing;

final class FeedDto extends Dto
{
    public readonly string|Missing $name;
    public readonly AuthType|Missing $auth;
    public readonly string|Missing $username;
    public readonly string|Missing $password;
    public readonly string|Missing $query;
    public readonly array|Missing $fields;

    public static function fromRequest(FeedStoreRequest|FeedUpdateRequest $request): self
    {
        return new self(
            name: $request->input('name', new Missing()),
            query: $request->input('query', new Missing()),
            auth: $request->has('auth') ? $request->enum('auth', AuthType::class) : new Missing(),
            username: $request->input('username', new Missing()),
            password: $request->input('password', new Missing()),
            fields: $request->input('fields', new Missing()),
        );
    }
}
