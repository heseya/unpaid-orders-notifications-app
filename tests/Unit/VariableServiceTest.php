<?php

declare(strict_types=1);

use App\Enums\FieldType;
use App\Resolvers\CoverResolver;
use App\Services\VariableService;

$service = new VariableService();

it('resolves standard field type', function () use ($service) {
    expect($service->resolveType('name'))->toBe(FieldType::STANDARD);
});

it('resolves global var field type', function () use ($service) {
    expect($service->resolveType('$shipping_price'))->toBe(FieldType::VAR_GLOBAL);
});

it('resolves local var field type', function () use ($service) {
    expect($service->resolveType('#cover'))->toBe(FieldType::VAR_LOCAL);
});

it('get resolvers for standard field', function () use ($service) {
    expect($service->getResolver('name'))->toBe(null);
});

it('get resolvers for var field', function () use ($service) {
    expect($service->getResolver('#cover'))
        ->toBeInstanceOf(CoverResolver::class);
});
