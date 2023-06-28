<?php

declare(strict_types=1);

use App\Resolvers\CategoryResolver;
use Illuminate\Support\Str;

it('resolves field', function () {
    $id = Str::uuid();
    $parentId = Str::uuid();

    expect(CategoryResolver::resolve(mockField(new CategoryResolver()), [
        'sets' => [
            [
                'id' => $id,
                'name' => 'Monitors',
                'parent_id' => $parentId,
                'children_ids' => [],
            ],
            [
                'id' => $parentId,
                'name' => 'Peripherals',
                'parent_id' => null,
                'children_ids' => [
                    $id,
                ],
            ],
        ],
    ]))->toEqual('Peripherals\\Monitors');
});

it('resolves field when empty', function () {
    expect(CategoryResolver::resolve(mockField(new CategoryResolver()), []))->toEqual('');
});
