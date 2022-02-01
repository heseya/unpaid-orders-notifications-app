<?php

return [

    "required" => [
        "products" => [
            "products.show",
            "products.show_details",
        ],
        "products-private" => [
            "products.show",
            "products.show_details",
            "products.show_hidden",
        ],
        "orders" => [
            "orders.show",
            "orders.show_details",
        ],
        "items" => [
            "items.show",
            "items.show_details",
        ],
    ],

    "internal" => [
        [
            "name" => "config",
            "description" => "Allows to configure the app",
            "display_name" => env("APP_NAME") . " configuration",
        ],
    ],

];
