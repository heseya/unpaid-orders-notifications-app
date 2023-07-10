<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Feed;
use App\Models\Field;
use App\Resolvers\LocalResolver;
use App\Services\Contracts\FileServiceContract;
use Illuminate\Support\Str;

final readonly class FileServiceXML implements FileServiceContract
{
    public function buildHeader(Feed $feed): string
    {
        return '<?xml version="1.0" encoding="utf-8"?><offers xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="1">';
    }

    /**
     * @param Field[] $fields
     */
    public function buildRow(array $fields, array $response): string
    {
        $cells = ['<o>'];

        foreach ($fields as $field) {
            $value = Str::of($field->resolver instanceof LocalResolver ?
                $field->getLocalValue($response) :
                $field->getGlobalValue());

            if ($field->resolver::ESCAPE) {
                $value = $value
                    ->replace('', '')
                    ->start('<![CDATA[')
                    ->append(']]>');
            }

            $cells[] = $value
                ->start("<{$field->key}>")
                ->append("</{$field->key}>")
                ->toString();
        }

        $cells[] = '</o>';

        return implode('', $cells);
    }

    public function buildEnding(Feed $feed): string
    {
        return '</offers>';
    }
}
