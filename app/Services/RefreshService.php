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

        // create / overwrite temp file
        $tempFile = fopen($tempPath, 'w');
        fwrite($tempFile, $this->fileService->buildHeader($feed));
        fclose($tempFile);
        unset($tempFile);
        $fields = $this->variableService->resolve($feed);

        $processedRows = 0;
        $lastPage = 1; // Get at least once
        for ($page = 1; $page <= $lastPage; ++$page) {
            $response = $this->apiService->get($feed->api, $feed->query, ['page' => $page]);
            $lastPage = $response->json('meta.last_page');

            // append data
            $tempFile = fopen($tempPath, 'a');

            foreach ($response->json('data') as $responseObject) {
                fwrite($tempFile, $this->fileService->buildRow($fields, $responseObject));
                ++$processedRows;
            }

            // clear memory
            fclose($tempFile);
            unset($tempFile);
            unset($response);
        }

        // add ending to file
        $tempFile = fopen($tempPath, 'a');
        fwrite($tempFile, $this->fileService->buildEnding($feed));
        fclose($tempFile);

        // move temp file to right location
        rename($tempPath, $path);
        $feed->update([
            'refreshed_at' => Carbon::now(),
            'processed_rows' => $processedRows,
        ]);
    }
}
