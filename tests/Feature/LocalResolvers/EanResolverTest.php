<?php

declare(strict_types=1);

use App\Resolvers\EanResolver;

it('resolves field', function () {
    expect(EanResolver::resolve(mockField(new EanResolver()), [
        'attributes' => [
            [
                'name' => 'EAN',
                'selected_options' => [
                    [
                        'name' => '9788311169654',
                    ],
                ],
            ],
        ],
    ]))->toEqual('9788311169654');
});

it('resolves field when empty', function () {
    expect(EanResolver::resolve(mockField(new EanResolver()), []))->toEqual('');
});
