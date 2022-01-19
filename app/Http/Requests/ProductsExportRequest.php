<?php

namespace App\Http\Requests;

use App\Rules\Formats;
use Illuminate\Foundation\Http\FormRequest;

class ProductsExportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'api' => ['required', 'string'],
            'format' => ['nullable', 'string', new Formats()],
        ];
    }
}
