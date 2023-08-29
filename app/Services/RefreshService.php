<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Feed;
use App\Services\Contracts\ApiServiceContract;
use App\Services\Contracts\FileServiceContract;
use App\Services\Contracts\RefreshServiceContract;
use App\Services\Contracts\VariableServiceContract;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;

final readonly class RefreshService implements RefreshServiceContract
{
    public function __construct(
        private ApiServiceContract $apiService,
        private VariableServiceContract $variableService,
    ) {}

    public function refreshFeed(Feed $feed): void
    {
        $tempPath = storage_path('app/' . $feed->tempPath());
        $path = storage_path('app/' . $feed->path());

        /** @var FileServiceContract $fileService */
        $fileService = App::get($feed->format->service());

        // create / overwrite temp file
        $tempFile = fopen($tempPath, 'w');
        fwrite($tempFile, $fileService->buildHeader($feed));
        fclose($tempFile);
        unset($tempFile);
        $fields = $this->variableService->resolve($feed);

        $processedRows = 0;
        $lastPage = 1; // Get at least once
        $tempFile = fopen($tempPath, 'a'); // append data
        for ($page = 1; $page <= $lastPage; ++$page) {
            $pageRows = '';
            $response = $this->apiService->get($feed->api, "{$feed->query}&page={$page}");
            $lastPage = $response->json('meta.last_page');

            foreach ($response->json('data') as $responseObject) {
                $pageRows .= $fileService->buildRow($fields, $responseObject);
                ++$processedRows;
            }

            fwrite($tempFile, $pageRows);
            fflush($tempFile);
        }

        fwrite($tempFile, $fileService->buildEnding($feed));
        fclose($tempFile);

        rename($tempPath, $path); // move temp file to right location
        $feed->update([
            'refreshed_at' => Carbon::now(),
            'processed_rows' => $processedRows,
        ]);
    }
}
