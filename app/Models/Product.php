<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    protected $fillable = [
        'external_id',
        'name',
        'price',
        'image_url',
        'is_active',
        'sale_price',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'float',
        'sale_price' => 'float',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public static function getTwoRandomProducts(): array{
        if(static::active()->count() < 2){
            throw new \Exception("Not enough active products available.");
        }

        $minId = static::active()->min('id');
        $maxId = static::active()->max('id');

        $products = collect();
        $attempts = 0;

        while($products->count() < 2 && $attempts < 10){
            $attempts++;
            $randomId = rand($minId, $maxId);

            $product = static::active()->where('id', '>=', $randomId)->first(['id', 'name', 'image_url']);

            if($product && !$products->contains('id', $product->id)){
                $products->push($product);
            }
        }

        return $products->all();
    }

    public static function getPriceById(int $id): array{
        $product = static::active()->where('id', $id)->first(['price', 'sale_price']);

        return[
            'price' => (float) $product->price,
            'sale_price' => $product->sale_price !== null ? (float) $product->sale_price : null,
        ];
    }
}
