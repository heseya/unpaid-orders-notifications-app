<?php

declare(strict_types=1);

use Illuminate\Support\Env;

return [
    'name' => $appName = Env::get('APP_NAME', 'Unpaid orders notifications'),
    'author' => 'Heseya',
    'description' => Env::get('APP_DESCRIPTION'),
    'icon' => Env::get('APP_URL') . '/logo.png',
    'version' => '1.0.0',
    'api_version' => '^5.0.0',
    'microfrontend_url' => null,
    'licence_required' => Env::get('LICENSE_REQUIRED', false),
    'required_permissions' => [
        'auth.check_identity',
        'orders.show',
        'orders.show_details',
        'orders.show_hidden',
    ],
    'internal_permissions' => [
        [
            'name' => 'configure',
            'display_name' => "Permission to manage {$appName}",
            'unauthenticated' => false,
        ],
    ],
];
