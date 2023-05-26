<?php

declare(strict_types=1);

use App\Resolvers\SalePriceResolver;

it('resolve field', function () {
    expect(
        SalePriceResolver::resolve(mockField(new SalePriceResolver()), ['price_min' => 10])
    )->toEqual('10 PLN');
});

it('resolve field when there is no info', function () {
    expect(
        SalePriceResolver::resolve(mockField(new SalePriceResolver()), [])
    )->toEqual('0 PLN');
});
