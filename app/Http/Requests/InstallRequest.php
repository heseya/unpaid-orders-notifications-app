<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class InstallRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'api_url' => [
                'required',
                'string',
                Rule::unique('apis', 'url'),
            ],
            'api_name' => ['nullable', 'string'],
            'api_version' => ['required', 'string'],
            'licence_key' => ['nullable', 'string'],
            'integration_token' => ['required', 'string'],
            'refresh_token' => ['required', 'string'],
        ];
    }
}
