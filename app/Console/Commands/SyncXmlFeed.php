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

        $feeds = [
            'cz' => [
                'url' => 'https://bata-feed.s3.eu-central-1.amazonaws.com/feeds/google/google_cz.xml',
                'currency' => 'Kč'
                ],
            'en' => [
                'url' => 'https://bata-feed.s3.eu-central-1.amazonaws.com/feeds/google/google_eu_nl.xml',
                'currency' => '€'
            ]
        ];

        $totalProcessed = 0;
        $totalSkipped = 0;
        $hasError = false;

        foreach($feeds as $locale => $config){
            $this->line("URL: {$config['url']}");

            try{
                $stats = $importService->import($config['url'], $locale, $config['currency']);

                $this->comment("   -> Processed: {$stats['processed']}");
                $this->comment("   -> Skipped: {$stats['skipped']}");

                $totalProcessed += $stats['processed'];
                $totalSkipped += $stats['skipped'];

            } catch (\Throwable $e) {
                $this->error("   [!] Error while processing feed " . strtoupper($locale) . ": " . $e->getMessage());

                Log::error('XML sync failed for locale: ' . $locale, [
                    'error' => $e->getMessage(),
                    'url'   => $config['url'],
                ]);

                $hasError = true;
            }
        }

        $this->line("---------------------------------");
        $this->line("=== Synchronization completed ===");

        $this->table(
            ['Total Processed', 'Total Skipped'],
            [[$totalProcessed, $totalSkipped]]
        );

        return $hasError ? Command::FAILURE : Command::SUCCESS;
    }
}
