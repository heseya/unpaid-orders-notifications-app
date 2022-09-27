<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfigStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'store_front_url' => ['required', 'url'],
            'product_type_set_parent_filter' => ['string'],
            'product_type_set_no_parent_filter' => ['boolean'],
            'google_custom_label_metatag' => ['string'],
        ];
    }
}
