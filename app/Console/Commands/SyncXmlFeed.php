<?php

namespace App\Console\Commands;

use App\Services\XmlImportService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncXmlFeed extends Command
{
    protected $signature = 'sync:xml';
    protected $description = 'Download products from XML feed';

    public function handle(XmlImportService $importService): int
    {
        $this->info("Starting XML feed synchronization...");

        try{
            $stats = $importService->import();

            $this->table(['Vloženo', 'Přeskočeno'],
            [[$stats['processed'], $stats['skipped']]]);

            $this->info("XML feed synchronization completed successfully.");

            return Command::SUCCESS;

        } catch (\Throwable $e) {
            $this->error("Error occurred while synchronizing XML feed: " . $e->getMessage());

            Log::error('XML sync failed: ', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return Command::FAILURE;
        }
    }
}
