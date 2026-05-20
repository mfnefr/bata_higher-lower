<?php

namespace App\Console\Commands;

use App\Services\XmlImportService;
use Illuminate\Console\Command;

class SyncXmlFeed extends Command
{
    protected $signature = 'sync:xml';
    protected $description = 'Download products from XML feed';
    /**
     * Execute the console command.
     */
    public function handle(XmlImportService $importService): int
    {
        $this->info("Starting XML feed synchronization...");

        try{
            $stats = $importService->import();

            $this->table(['Vloženo', 'Aktualizováno', 'Přeskočeno'], 
            [[$stats['inserted'], $stats['updated'], $stats['skipped']]]);

            $this->info("XML feed synchronization completed successfully.");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Error occurred while synchronizing XML feed: " . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
