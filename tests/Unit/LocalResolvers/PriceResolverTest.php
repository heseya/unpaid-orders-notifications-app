<?php

declare(strict_types=1);

use App\Resolvers\PriceResolver;

it('resolve field', function () {
    expect(PriceResolver::resolve(['price_min' => 10]))->toEqual('10 PLN');
});

it('resolve field when price initial is set', function () {
    expect(PriceResolver::resolve(['price_min_initial' => 20]))->toEqual('20 PLN');
});

it('resolve field when there is no info', function () {
    expect(PriceResolver::resolve([]))->toEqual('0 PLN');
});
