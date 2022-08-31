<?php

$app_name = strlen(env('APP_NAME', 'Exporter')) > 0
    ? env('APP_NAME', 'Exporter') : 'Exporter';
$unauthenticated_permissions = explode(',', env('UNAUTHENTICATED_PERMISSIONS'));

return [

    'base' => [
        'auth.check_identity',
    ],

    'required' => [
        'products' => [
            'products.show',
            'products.show_details',
        ],
        'products-private' => [
            'products.show',
            'products.show_details',
            'products.show_hidden',
        ],
        'orders' => [
            'orders.show',
            'orders.show_details',
        ],
        'items' => [
            'items.show',
            'items.show_details',
        ],
    ],

    'internal' => [
        'configure' => [
            'name' => 'configure',
            'display_name' => 'Możliwość zmiany ustawień ' . $app_name,
        ],
        'products' => [
            'name' => 'show_products',
            'display_name' => 'Możliwość wyświetlania raportu publicznych produktów',
            'unauthenticated' => in_array('products', $unauthenticated_permissions),
        ],
        'products-private' => [
            'name' => 'show_products_private',
            'display_name' => 'Możliwość wyświetlania raportu wszystkich produktów',
            'unauthenticated' => in_array('products-private', $unauthenticated_permissions),
        ],
        'orders' => [
            'name' => 'show_orders',
            'display_name' => 'Możliwość wyświetlania raportu zamówień',
            'unauthenticated' => in_array('orders', $unauthenticated_permissions),
        ],
        'items' => [
            'name' => 'show_items',
            'display_name' => 'Możliwość wyświetlania raportu magazynowego',
            'unauthenticated' => in_array('items', $unauthenticated_permissions),
        ],
    ],

];
