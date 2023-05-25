<?php

declare(strict_types=1);

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

    expect(ShippingPriceResolver::resolve(mockField(new ShippingPriceResolver())))->toBe('PL:10 PLN');
});
