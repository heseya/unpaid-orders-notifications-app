<?php

declare(strict_types=1);

use App\Resolvers\FileCreatedAtResolver;

it('resolves field', function () {
    $this->travelTo('2020-01-01T09:10:00+00:00');

    expect(FileCreatedAtResolver::resolve(mockField(new FileCreatedAtResolver())))
        ->toBe('2020-01-01T09:10:00+00:00');
});
