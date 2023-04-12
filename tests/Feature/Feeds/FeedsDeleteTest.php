<?php

use App\Models\Feed;

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing, deleteJson};

it('doesn\'t allow delete when unauthorized', function () {
    $feed = Feed::factory()->create(['api_id' => mockApi()->getKey()]);

    deleteJson("/feeds/{$feed->getKey()}")->assertForbidden();
});

it('doesn\'t allow delete not owned feed', function () {
    $api = mockApi();
    actingAs(mockUser($api));

    $feed = Feed::factory()->create(['api_id' => mockApi()->getKey()]);

    deleteJson("/feeds/{$feed->getKey()}", [
        'name' => 'Sample Feed',
        'query' => '/products?public=1',
        'fields' => ['test' => 'test'],
    ])->assertForbidden();
});


it('deletes feeds', function () {
    $api = mockApi();
    actingAs(mockUser($api));

    $feed = Feed::factory()->create(['api_id' => $api->getKey()]);

    deleteJson("/feeds/{$feed->getKey()}")->assertNoContent();

    assertDatabaseMissing('feeds', [
        'id' => $feed->getKey(),
    ]);
});
