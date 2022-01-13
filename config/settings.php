<?php

use Illuminate\Support\Str;

$appUrl = env('APP_URL', 'http://localhost');
$appUrl = Str::endsWith($appUrl, '/')
    ? Str::substr($appUrl, 0, Str::length($appUrl) - 1)
    : $appUrl;

return [

    'store_front_url' => [
        'key' => 'store_front_url',
        'label' => 'Storefront URL',
        'placeholder' => 'https://example.store/product/',
        'type' => 'text',
        'default_value' => null,
        'required' => true,
        'options' => [],
    ],
];
