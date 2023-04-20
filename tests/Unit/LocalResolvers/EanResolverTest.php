<?php

use App\Resolvers\EanResolver;

it('resolves field', function () {
    expect(EanResolver::resolve([
        'attributes' => [
            [
                'slug' => 'ean',
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
    expect(EanResolver::resolve([]))->toEqual('');
});
