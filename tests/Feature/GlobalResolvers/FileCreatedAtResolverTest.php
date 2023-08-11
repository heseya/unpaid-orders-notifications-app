<?php

declare(strict_types=1);

use App\Resolvers\ShippingPriceResolver;
use Illuminate\Support\Facades\Http;

it('resolves field', function () {
    $this->travelTo('2020-01-01T10:10:00+01:00');

    expect(ShippingPriceResolver::resolve(mockField(new ShippingPriceResolver())))
        ->toBe('2020-01-01T10:10:00+01:00');
});
