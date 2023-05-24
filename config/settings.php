<?php

declare(strict_types=1);

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

    'product_type_set_parent_filter' => [
        'key' => 'product_type_set_parent_filter',
        'label' => 'Product type set parent id filter',
        'placeholder' => 'e7a4486f-1ee0-49d7-91b8-41d409a8f37d',
        'type' => 'text',
        'default_value' => null,
        'required' => false,
        'options' => [],
    ],

    'product_type_set_no_parent_filter' => [
        'key' => 'product_type_set_no_parent_filter',
        'label' => 'Product type set no parent filter',
        'placeholder' => false,
        'type' => 'checkbox',
        'default_value' => false,
        'required' => false,
        'options' => [],
    ],

    'google_custom_label_metatag' => [
        'key' => 'google_custom_label_metatag',
        'label' => 'Google custom label metatag',
        'placeholder' => 'https://example.store/product/',
        'type' => 'text',
        'default_value' => null,
        'required' => false,
        'options' => [],
    ],

];
