<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use XMLReader;

class XmlImportService{
    private string $namespace = 'http://base.google.com/ns/1.0';

    public function import(string $feedUrl, string $locale): array{
        $stats = ['processed' => 0, 'skipped' => 0];
        $processedMpn = [];
        $batch = [];

        $reader = new XMLReader();

        if(!$reader->open($feedUrl)){
            throw new \RuntimeException("Failed to open XML feed at {$feedUrl}");
        }

        while($reader->read()){
            if($reader->nodeType === XMLReader::ELEMENT && $reader->localName === 'item'){
                $xml = simplexml_load_string($reader->readOuterXML());

                $itemData = $this->processItem($xml, $processedMpn, $locale);

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

        $this->deactivateMissingProducts($locale);

        return $stats;
    }

    private function processItem(\SimpleXMLElement $item, array &$processedMpn, string $locale): ?array{
        $g = $item->children($this->namespace);

        $externalId = (string) $g->id;
        $name = (string) $g->title;
        $priceRaw = (string) $g->price;
        $salePriceRaw = (string) $g->sale_price;
        $availability = (string) $g->availability;
        $mpn = (string) $g->mpn;
        $imageUrl = (string) $g->image_link;

        $price = $this->parsePrice($priceRaw);
        $salePrice = !empty($salePriceRaw) ? $this->parsePrice($salePriceRaw) : null;

        if(empty($imageUrl) || $price <= 0){
            return null;
        }

        $available = strtolower(trim($availability));
        if($available !== 'in stock' && $available !== 'in_stock'){
            return null;
        }

        if(!empty($mpn)){
            if(isset($processedMpn[$mpn])){
                return null;
            }
            $processedMpn[$mpn] = true;
        }

        return [
            'external_id' => $externalId,
            'locale' => $locale,
            'name' => $name,
            'price' => $price,
            'sale_price' => $salePrice !== null ? number_format($salePrice, 2, '.', '') : null,
            'image_url' => $imageUrl,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function insertBatch(array $batch): void{
        Product::upsert(
            $batch,
            ['external_id', 'locale'],
            ['name', 'price', 'sale_price', 'image_url', 'is_active', 'updated_at']
        );
    }

    private function parsePrice(string $priceRaw): float{
        $parts = explode(' ', trim($priceRaw));
        return (float) ($parts[0] ?? 0);
    }

    private function deactivateMissingProducts(string $locale): void
    {
        DB::table('products')->where('locale', $locale)->where('is_active', true)->where('updated_at', '<', now()->subHours(24))
            ->update(['is_active' => false]);
    }
}
