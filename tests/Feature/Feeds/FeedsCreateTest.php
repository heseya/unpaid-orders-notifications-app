<?php

declare(strict_types=1);

use App\Enums\AuthType;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

it('doesn\'t allow create when unauthorized', function () {
    postJson('/feeds')->assertForbidden();
});

it('creates feeds', function () {
    $api = mockApi();
    actingAs(mockUser($api));

    postJson('/feeds', [
        'name' => 'Sample Feed',
        'auth' => AuthType::NO->value,
        'query' => '/products?public=1',
        'fields' => ['test' => 'test'],
    ])->assertCreated();

    assertDatabaseHas('feeds', [
        'api_id' => $api->getKey(),
        'name' => 'Sample Feed',
        'query' => '/products?public=1',
        'fields' => '{"test":"test"}',
    ]);
});
