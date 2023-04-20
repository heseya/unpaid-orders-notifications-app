<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['string'],
            'query' => ['string'],
            'fields' => ['array'],
            'fields.*' => ['string'],
        ];
    }
}
