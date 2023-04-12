<?php

use App\Models\Feed;
use App\Services\FileService;

it('generates file headers', function () {
    $service = new FileService();
    $feed = new Feed(['fields' => [
        'test' => 'test-value',
        'test1' => 'test-value-1',
    ]]);

    expect($service->buildHeaders($feed))
        ->toEqual([
            'test',
            'test1',
        ]);
});

it('generates file cell', function () {
    $service = new FileService();
    $feed = new Feed(['fields' => [
        'test' => 'key',
        'test1' => 'key-1.key-1',
        'test2' => 'key-2',
    ]]);

    $response = [
        'key' => 'value',
        'key-1' => [
            'key-1' => 'value-1',
            'key-2' => 'value-2',
        ],
    ];

    expect($service->buildCell($feed, $response))
        ->toEqual([
            '"value"',
            '"value-1"',
            '""',
        ]);
});
