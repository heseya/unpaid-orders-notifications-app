<?php

declare(strict_types=1);

use App\Resolvers\PriceResolver;

it('resolve field', function () {
    expect(PriceResolver::resolve(
        mockField(new PriceResolver()),
        ['price_min' => 10],
    ))->toEqual('10 PLN');
});

it('resolve field when price initial is set', function () {
    expect(PriceResolver::resolve(
        mockField(new PriceResolver()),
        ['price_min_initial' => 20],
    ))->toEqual('20 PLN');
});

it('resolve field when there is no info', function () {
    expect(PriceResolver::resolve(mockField(new PriceResolver()), []))->toEqual('0 PLN');
});
