<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ConfigUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string[]>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:200'],
            'payment_url' => ['required', 'string', 'max:400'],
            'orders_from_hours' => ['required', 'integer', 'gte:0', 'lte:1500'],
        ];
    }
}
