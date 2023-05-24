<?php

declare(strict_types=1);

use App\Models\Feed;
use App\Resolvers\ShippingPriceResolver;
use Illuminate\Support\Facades\Http;

it('resolves field', function () {
    Http::fake([
        '*' => [
            'data' => [
                [
                    'price' => 20,
                ],
                [
                    'price' => 10,
                ],
                [
                    'price' => 15,
                ],
            ],
        ],
    ]);

    $api = mockApi();
    $feed = Feed::factory()->create(['api_id' => $api->getKey()]);

    expect(ShippingPriceResolver::resolve($feed))->toBe('PL:10 PLN');
});
