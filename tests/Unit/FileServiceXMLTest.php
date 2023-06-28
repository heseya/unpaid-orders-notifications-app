<?php

declare(strict_types=1);

use App\Models\Feed;
use App\Services\FileServiceXML;
use App\Services\VariableService;

it('generates file cell', function () {
    $service = new FileServiceXML();
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
        ->toEqual('<o><test>value</test><test1>value-1</test1><test2></test2></o>');
});
