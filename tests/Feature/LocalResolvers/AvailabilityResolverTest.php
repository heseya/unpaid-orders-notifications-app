<?php

declare(strict_types=1);

use App\Resolvers\AvailabilityResolver;

it('resolve availability field', function () {
    expect(AvailabilityResolver::resolve(
        mockField(new AvailabilityResolver()),
        ['available' => true],
    ))->toEqual('in stock');
});

it('resolve availability field when out of stock', function () {
    expect(AvailabilityResolver::resolve(
        mockField(new AvailabilityResolver()),
        ['available' => false],
    ))->toEqual('out of stock');
});

it('resolve availability field when there is no info', function () {
    expect(AvailabilityResolver::resolve(
        mockField(new AvailabilityResolver()),
        [],
    ))->toEqual('out of stock');
});
