<?php

declare(strict_types=1);

use App\Resolvers\AttrsResolver;

it('resolve field', function () {
    expect(AttrsResolver::resolve(mockField(new AttrsResolver()), [
        'attributes' => [
            [
                'name' => 'test',
                'selected_options' => [
                    [
                        'name' => 'test-value',
                    ],
                ],
            ],
            [
                'name' => 'test2',
                'selected_options' => [
                    [
                        'name' => 'test-value2',
                    ],
                ],
            ],
        ],
    ]))->toEqual(
        '<a name="test"><![CDATA[test-value]]></a><a name="test2"><![CDATA[test-value2]]></a>'
    );
});

it('resolve field when there is no attributes', function () {
    expect(AttrsResolver::resolve(
        mockField(new AttrsResolver()),
        [],
    ))->toEqual('');
});
