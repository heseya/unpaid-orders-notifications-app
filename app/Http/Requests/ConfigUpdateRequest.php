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
            'name' => ['required', 'string', 'lte:200'],
            'payment_url' => ['required', 'string', 'lte:400'],
            'orders_from_days' => ['required', 'integer', 'gte:0', 'lte:64'],
        ];
    }
}
