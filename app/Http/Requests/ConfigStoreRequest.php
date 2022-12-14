<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfigStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'store_front_url' => ['required', 'url'],
            'product_type_set_parent_filter' => ['nullable', 'string'],
            'product_type_set_no_parent_filter' => ['nullable', 'boolean'],
            'google_custom_label_metatag' => ['nullable', 'string'],
            'products_limit' => ['integer'],
        ];
    }
}
