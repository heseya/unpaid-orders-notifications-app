<?php

use App\Models\Feed;

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing, deleteJson, patchJson};

it('doesn\'t allow update when unauthorized', function () {
    $feed = Feed::factory()->create(['api_id' => mockApi()->getKey()]);

    patchJson("/feeds/{$feed->getKey()}")->assertForbidden();
});

it('doesn\'t allow update not owned feed', function () {
    $api = mockApi();
    actingAs(mockUser($api));

    $feed = Feed::factory()->create(['api_id' => mockApi()->getKey()]);

    patchJson("/feeds/{$feed->getKey()}", [
        'name' => 'New Name',
    ])->assertForbidden();

    assertDatabaseHas('feeds', [
        'id' => $feed->getKey(),
        'name' => $feed->name,
    ]);
});


it('deletes feeds', function () {
    $api = mockApi();
    actingAs(mockUser($api));

    $feed = Feed::factory()->create(['api_id' => $api->getKey()]);

    patchJson("/feeds/{$feed->getKey()}", [
        'name' => 'New Name',
    ])->assertOk();

    assertDatabaseHas('feeds', [
        'id' => $feed->getKey(),
        'name' => 'New Name',
    ]);
});
