<?php

declare(strict_types=1);

use App\Resolvers\CoverResolver;
use App\Resolvers\ResponseResolver;
use App\Resolvers\ShippingPriceResolver;
use App\Resolvers\StringResolver;
use App\Services\VariableService;

$service = new VariableService();

it('get resolvers for standard field', function () use ($service) {
    expect($service->getResolver('$name'))
        ->toBeInstanceOf(ResponseResolver::class);
});

it('get resolvers for string field', function () use ($service) {
    expect($service->getResolver('name'))
        ->toBeInstanceOf(StringResolver::class);
});

it('get resolvers for local var field', function () use ($service) {
    expect($service->getResolver('#cover'))
        ->toBeInstanceOf(CoverResolver::class);
});

it('get resolvers for global var field', function () use ($service) {
    expect($service->getResolver('@shipping_price'))
        ->toBeInstanceOf(ShippingPriceResolver::class);
});
