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
        $activeIds = static::active()->pluck('id')->toArray();

        if(static::active()->count() < 2){
            throw new \Exception("Not enough active products available.");
        }

        $randomIds = array_rand($activeIds, 2);

        $products = static::active()->whereIn('id',
            [ $activeIds[$randomIds[0]], $activeIds[$randomIds[1]] ]
        )->get(['id', 'name', 'image_url']);

        return $products->shuffle()->all();
    }

    public static function getPriceById(int $id): array{
        $product = static::active()->where('id', $id)->first(['price', 'sale_price']);

        return[
            'price' => (float) $product->price,
            'sale_price' => $product->sale_price !== null ? (float) $product->sale_price : null,
        ];
    }
}
