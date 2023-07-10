<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\AuthType;
use App\Enums\FileFormat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class FeedStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:64'],
            'format' => ['required', new Enum(FileFormat::class)],
            'query' => ['required', 'string', 'max:1000'],
            'fields' => ['required', 'array'],
            'fields.*' => ['string'],

            // auth
            'auth' => ['required', new Enum(AuthType::class)],
            'username' => ['required_if:auth,basic', 'missing_unless:auth,basic', 'string', 'max:64'],
            'password' => ['required_if:auth,basic', 'missing_unless:auth,basic', 'string', 'max:64'],
        ];
    }
}
