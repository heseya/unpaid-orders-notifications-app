<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'query' => ['required', 'string'],
            'fields' => ['required', 'array'],
            'fields.*' => ['string'],
        ];
    }
}
