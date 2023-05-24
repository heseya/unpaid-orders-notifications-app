<?php

declare(strict_types=1);

use App\Resolvers\CoverResolver;

it('resolve cover field', function () {
    expect(CoverResolver::resolve([
        'gallery' => [
            [
                'url' => 'http://example.com/img.png',
                'type' => 'photo',
            ],
        ],
    ]))->toEqual('http://example.com/img.png');
});

it('resolve cover field when first media is video', function () {
    expect(CoverResolver::resolve([
        'gallery' => [
            [
                'url' => 'http://example.com/video.png',
                'type' => 'video',
            ],
            [
                'url' => 'http://example.com/img.png',
                'type' => 'photo',
            ],
        ],
    ]))->toEqual('http://example.com/img.png');
});

it('resolve cover field when there is no image', function () {
    expect(CoverResolver::resolve([]))->toEqual('');
});
