<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Feed;
use App\Services\Contracts\ApiServiceContract;
use App\Services\Contracts\FileServiceContract;
use App\Services\Contracts\RefreshServiceContract;
use App\Services\Contracts\VariableServiceContract;
use Illuminate\Support\Carbon;

final readonly class RefreshService implements RefreshServiceContract
{
    public function __construct(
        private ApiServiceContract $apiService,
        private FileServiceContract $fileService,
        private VariableServiceContract $variableService,
    ) {
    }

    public function refreshFeed(Feed $feed): void
    {
        $tempPath = storage_path('app/' . $feed->tempPath());
        $path = storage_path('app/' . $feed->path());

        // TODO: remove this ugly shit
        $query = $feed->query . '&force_database_search=1';

        // create / overwrite temp file
        $tempFile = fopen($tempPath, 'w');
        fwrite($tempFile, implode(',', $this->fileService->buildHeaders($feed)) . "\n");
        fclose($tempFile);
        $tempFile = null;
        $fields = $this->variableService->resolve($feed);

        $lastPage = 1; // Get at least once
        for ($page = 1; $page <= $lastPage; ++$page) {
            $response = $this->apiService->get($feed->api, $query);
            $lastPage = $response->json('meta.last_page');

            // append data
            $tempFile = fopen($tempPath, 'a');

            foreach ($response->json('data') as $responseObject) {
                fwrite($tempFile, implode(',', $this->fileService->buildCell(
                    $fields,
                    $responseObject,
                )) . "\n");
            }

            // clear memory
            fclose($tempFile);
            $tempFile = null;
            $response = null;
        }

        // move temp file to right location
        rename($tempPath, $path);
        $feed->update([
            'refreshed_at' => Carbon::now(),
        ]);
    }
}
