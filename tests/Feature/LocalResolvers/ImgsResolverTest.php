<?php

declare(strict_types=1);

use App\Resolvers\ImgsResolver;

it('resolve field', function () {
    expect(ImgsResolver::resolve(mockField(new ImgsResolver()), [
        'gallery' => [
            [
                'url' => 'http://example.com/video.png',
                'type' => 'video',
            ],
            [
                'url' => 'http://example.com/img.png',
                'type' => 'photo',
            ],
            [
                'url' => 'http://example.com/img2.png',
                'type' => 'photo',
            ],
        ],
    ]))->toEqual(
        '<main url="http://example.com/img.png"/><i url="http://example.com/img2.png"/>'
    );
});

it('resolve field when there is no image', function () {
    expect(ImgsResolver::resolve(
        mockField(new ImgsResolver()),
        [],
    ))->toEqual('');
});
