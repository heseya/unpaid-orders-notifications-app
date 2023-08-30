<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Api;
use App\Services\SendService;
use Illuminate\Console\Command;

final class Send extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails to clients';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Starting...');

        $service = app(SendService::class);

        foreach (Api::all() as $api) {
            $this->info("{$api->url} processing...");
            $emailsSent = $service->sendForApi($api);
            $this->info("{$api->url} done ({$emailsSent} emails sent).");
        }

        $this->info('All done.');
    }
}
