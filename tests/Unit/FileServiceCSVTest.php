<?php

declare(strict_types=1);

use App\Models\Feed;
use App\Services\FileServiceCSV;
use App\Services\VariableService;

$service = new FileServiceCSV();

it('generates file headers', function () use ($service) {
    $feed = new Feed(['fields' => [
        'test' => 'test-value',
        'test1' => 'test-value-1',
    ]]);

    expect($service->buildHeader($feed))
        ->toEqual("test,test1\n");
});

it('generates file cell', function () use ($service) {
    $feed = new Feed(['fields' => [
        'test' => '$key',
        'test1' => '$key-1.key-1',
        'test2' => '$key-2',
    ]]);
    $fields = (new VariableService())->resolve($feed);

    $response = [
        'key' => 'value',
        'key-1' => [
            'key-1' => 'value-1',
            'key-2' => 'value-2',
        ],
    ];

    expect($service->buildRow($fields, $response))
        ->toEqual('"value","value-1",""' . "\n");
});
