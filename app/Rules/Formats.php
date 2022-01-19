<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Config;

class Formats implements Rule
{
    protected $formats;

    public function __construct()
    {
        $this->formats = Config::get('export.formats');
    }

    public function passes($attribute, $value): bool
    {
        return in_array($value, $this->formats);
    }

    public function message(): string
    {
        return 'The :attribute must be one of ' . implode(', ', $this->formats);
    }
}
