<?php

use function Pest\Laravel\{actingAs, getJson};

it('doesn\'t show feeds when unauthorized', function () {
    getJson('/feeds')->assertForbidden();
});

it('shows feeds', function () {
    $api = mockApi();
    actingAs(mockUser($api));

    $api->feeds()->create([
        'name' => 'Test Feed',
        'fields' => '{}',
    ]);

    getJson('/feeds')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'name' => 'Test Feed',
            'fields' => '{}',
        ]);
});
