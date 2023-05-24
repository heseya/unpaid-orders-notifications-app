<?php

declare(strict_types=1);

use App\Resolvers\AvailabilityResolver;

it('resolve availability field', function () {
    expect(AvailabilityResolver::resolve(['available' => true]))->toEqual('in stock');
});

it('resolve availability field when out of stock', function () {
    expect(AvailabilityResolver::resolve(['available' => false]))->toEqual('out of stock');
});

it('resolve availability field when there is no info', function () {
    expect(AvailabilityResolver::resolve([]))->toEqual('out of stock');
});
