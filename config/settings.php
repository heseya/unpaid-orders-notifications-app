<?php

use Illuminate\Support\Str;

$appUrl = env('APP_URL', 'http://localhost');
$appUrl = Str::endsWith($appUrl, '/')
    ? Str::substr($appUrl, 0, Str::length($appUrl) - 1)
    : $appUrl;

return [

    'fields' => [
        [
            'key' => 'products_url',
            'label' => 'Storefront product URL without product slug',
            'placeholder' => 'https://example.store/product/',
            'type' => 'text',
            'default_value' => null,
            'required' => true,
            'options' => [],
        ],
        [
            'key' => 'csv_url',
            'label' => 'CSV formated Facebook Catalog url',
            'placeholder' => $appUrl . '/products?api=api_url',
            'type' => 'text',
            'default_value' => null,
            'required' => false,
            'options' => [],
        ],
    ],

];
