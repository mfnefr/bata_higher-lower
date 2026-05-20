<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use XMLReader;

class XmlImportService{
    private string $feedUrl = 'https://bata-feed.s3.eu-central-1.amazonaws.com/feeds/google/google_eu_nl.xml';

    private string $namespace = 'http://base.google.com/ns/1.0';

    public function import(): array{
        $stats = ['inserted' => 0, 'updated' => 0, 'skipped' => 0];

        $processedMpn = [];

        $reader = new XMLReader();

        if(!$reader->open($this->feedUrl)){
            throw new \RuntimeException("Failed to open XML feed at {$this->feedUrl}");
        }

        while($reader->read()){
            if($reader->nodeType === XMLReader::ELEMENT && $reader->localName === 'item'){
                $xml = simplexml_load_string($reader->readOuterXML());
                $result = $this->processItem($xml, $processedMpn);
                $stats[$result]++;
            }
        }

        $reader->close();
        $this->deactivateMissingProducts();

        return $stats;
    }

    private function processItem(\SimpleXMLElement $item, array &$processedMpn): string{
        $g = $item->children($this->namespace);

        $externalId = (string) $g->id;
        $name = (string) $g->title;
        $priceRaw = (string) $g->price;
        $availability = (string) $g->availability;
        $mpn = (string) $g->mpn;
        $imageUrl = (string) $g->image_link;

        $price = $this->parsePrice($priceRaw);

        if(empty($imageUrl) || $price <= 0 || $availability !== 'in stock' || in_array($mpn, $processedMpn)){
            return 'skipped';
        }

        $processedMpn[] = $mpn;
        $product = Product::where('external_id', $externalId)->first();

        if ($product) {
            $product->name      = $name;
            $product->price     = number_format($price, 2, '.', '');
            $product->image_url = $imageUrl;
            $product->is_active = true;
            $product->save();

            return 'updated';
        } else {
            $product = new Product();
            $product->external_id = $externalId;
            $product->name        = $name;
            $product->price       = number_format($price, 2, '.', '');
            $product->image_url   = $imageUrl;
            $product->is_active   = true;
            $product->save();

            return 'inserted';
        }
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
