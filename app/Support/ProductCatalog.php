<?php

namespace App\Support;

use App\Models\Product;

class ProductCatalog
{
    public static function all(): array
    {
        return Product::query()
            ->orderBy('id')
            ->get()
            ->map(fn (Product $product) => self::toArray($product))
            ->all();
    }

    public static function find(int $productId): ?array
    {
        $product = Product::query()->find($productId);

        return $product ? self::toArray($product) : null;
    }

    private static function toArray(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'image' => $product->image_url,
            'is_sold_out' => $product->is_sold_out,
        ];
    }
}
