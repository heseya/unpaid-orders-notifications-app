<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\AuthType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class FeedUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['string'],
            'query' => ['string'],
            'fields' => ['array'],
            'fields.*' => ['string'],

            // auth
            'auth' => [new Enum(AuthType::class)],
            'username' => ['required_if:auth,basic', 'string', 'max:64'],
            'password' => ['required_if:auth,basic', 'string', 'max:64'],
        ];
    }
}
