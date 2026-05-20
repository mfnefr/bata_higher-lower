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
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public static function getTwoRandomProducts(): array{
        $maxId = static::active()->max('id');

        $products = collect();

        while($products->count() < 2){
            $randomId = rand(1, $maxId);

            $product = static::active()->where('id', '>=', $randomId)->first(['id', 'name', 'image_url']);

            if($product && !$products->contains('id', $product->id)){ //pluck
                $products->push($product);
            }
        }

        return $products->all();
    }

    public static function getPriceById(int $id): float{
        return static::active()->where('id', $id)->value('price');
    }
}
