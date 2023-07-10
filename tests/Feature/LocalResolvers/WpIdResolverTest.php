<?php

declare(strict_types=1);

use App\Resolvers\WpIdResolver;

it('resolve field', function () {
    expect(WpIdResolver::resolve(mockField(new WpIdResolver()), [
        'id' => 'test',
        'metadata_private' => [
            'wp_id' => '123',
        ],
    ]))->toEqual('123');
});

it('resolve field when no metadata', function () {
    expect(WpIdResolver::resolve(mockField(new WpIdResolver()), [
        'id' => 'test',
    ]))->toEqual('test');
});
