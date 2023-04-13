<?php

namespace App\Templates;

class GoogleProductsFeedTemplate implements FileTemplate
{
    public static function template(): array
    {
        return [
            'id' => 'id',
            'gtin' => 'attributes.ean',
            'title' => 'name',
            'description' => 'short_description',
            'availability' => 'availability', // in stock | out of stock
            'condition' => 'new',
            'price' => 'price_min_initial',
            'sale_price' => 'price_min',
            'link' => 'url',
            'image_link' => 'cover_url',
            'additional_image_link' => 'additional_image_url',
            'google_product_category' => 'google_product_category',
            'shipping(country:price)' => '',
            'product_type' => '',
        ];
    }
}
