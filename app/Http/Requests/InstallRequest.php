<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InstallRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "api_url" => [
                "string",
                Rule::unique('apis', 'url'),
            ],
            "api_name" => ["nullable", "string"],
            "api_version" => ["string"],
            "licence_key" => ["nullable", "string"],
            "integration_token" => ["string"],
            "refresh_token" => ["string"],
        ];
    }
}
