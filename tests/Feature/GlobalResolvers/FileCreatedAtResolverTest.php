<?php

declare(strict_types=1);

use App\Resolvers\FileCreatedAtResolver;

it('resolves field', function () {
    $this->travelTo('2020-01-01T10:10:00+01:00');

    expect(FileCreatedAtResolver::resolve(mockField(new FileCreatedAtResolver())))
        ->toBe('2020-01-01T10:10:00+01:00');
});
