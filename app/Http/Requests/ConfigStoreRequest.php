<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConfigStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'products_url' => ['required', 'url'],
        ];
    }
}
