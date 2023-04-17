<?php

namespace App\Http\Requests;

use App\Enums\AuthType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class FeedStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'query' => ['required', 'string'],
            'fields' => ['required', 'array'],
            'fields.*' => ['string'],

            // auth
            'auth' => ['required', new Enum(AuthType::class)],
            'username' => ['required_if:auth,basic', 'string', 'max:64'],
            'password' => ['required_if:auth,basic', 'string', 'max:64'],
        ];
    }
}
