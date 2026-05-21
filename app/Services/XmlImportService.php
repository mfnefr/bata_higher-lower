<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use XMLReader;

class XmlImportService{
    private string $feedUrl = 'https://bata-feed.s3.eu-central-1.amazonaws.com/feeds/google/google_eu_nl.xml';
    private string $namespace = 'http://base.google.com/ns/1.0';

    public function import(): array{
        $stats = ['processed' => 0, 'skipped' => 0];
        $processedMpn = [];
        $batch = [];

        $reader = new XMLReader();

        if(!$reader->open($this->feedUrl)){
            throw new \RuntimeException("Failed to open XML feed at {$this->feedUrl}");
        }

        while($reader->read()){
            if($reader->nodeType === XMLReader::ELEMENT && $reader->localName === 'item'){
                $xml = simplexml_load_string($reader->readOuterXML());
                $itemData = $this->processItem($xml, $processedMpn);

                if($itemData === null){
                    $stats['skipped']++;
                    continue;
                }

                $batch[] = $itemData;
                $stats['processed']++;

                if(count($batch) >= 100){
                    $this->insertBatch($batch);
                    $batch = [];
                }
            }
        }

        $reader->close();

        if(!empty($batch)){
            $this->insertBatch($batch);
        }

        $this->deactivateMissingProducts();

        return $stats;
    }

    private function processItem(\SimpleXMLElement $item, array &$processedMpn): ?array{
        $g = $item->children($this->namespace);

        $externalId = (string) $g->id;
        $name = (string) $g->title;
        $priceRaw = (string) $g->price;
        $availability = (string) $g->availability;
        $mpn = (string) $g->mpn;
        $imageUrl = (string) $g->image_link;

        $price = $this->parsePrice($priceRaw);

        if(empty($imageUrl) || $price <= 0 || $availability !== 'in stock' || isset($processedMpn[$mpn])){
            return null;
        }

        $processedMpn[$mpn] = true;
        
        return [
            'external_id' => $externalId,
            'name' => $name,
            'price' => $price,
            'image_url' => $imageUrl,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function insertBatch(array $batch): void{
        Product::upsert(
            $batch,
            ['external_id'],
            ['name', 'price', 'image_url', 'is_active', 'updated_at']
        );
    }

    private function parsePrice(string $priceRaw): float{
        $parts = explode(' ', trim($priceRaw));
        return (float) ($parts[0] ?? 0);
    }

    private function deactivateMissingProducts(): void
    {
        DB::table('products')->where('is_active', true)->where('updated_at', '<', now()->subHours(24))
            ->update(['is_active' => false]);
    }
}
