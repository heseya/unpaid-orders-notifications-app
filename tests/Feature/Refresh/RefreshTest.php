<?php

declare(strict_types=1);

use App\Models\Feed;
use Illuminate\Support\Facades\Http;

use function Pest\Laravel\artisan;

it('refresh feed', function () {
    $api = mockApi();
    $feed = Feed::factory()->create([
        'api_id' => $api->getKey(),
        'query' => '/products',
        'fields' => [
            'title' => 'name',
            'price' => 'price_min',
        ],
    ]);

    Http::fake([
        '*' => Http::response([
            'data' => [
                [
                    'name' => 'Test Product',
                    'price_min' => 200,
                    'price_max' => 300,
                ],
                [
                    'name' => 'Test Product 2',
                    'price_min' => 100,
                    'price_max' => 200,
                ],
            ],
            'meta' => [
                'last_page' => 1,
            ],
        ]),
    ]);

    artisan('refresh')
        ->assertSuccessful()
        ->expectsOutputToContain('1 feeds to process.')
        ->expectsOutputToContain('Processing ended.');

    expect(file_exists($feed->path()))->toBeTrue();

    $feed->refresh();
    expect($feed->refreshed_at)->toBeObject();
});
