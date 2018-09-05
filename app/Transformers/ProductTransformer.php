<?php

namespace App\Transformers;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    public function transform(Product $product)
    {
        return [
            'id' => $product->id,
            'title' => $product->title,
            'description' => $product->description,
            'on_sale' => $product->on_sale,
            'price' => $product->price,
            'stock' => $product->stock,
            'image' => $product->image,
            'image_url' => $product->image_url,
            'label' => $product->label,
            'sold_count' => $product->sold_count,
            'review_count' => $product->review_count,
        ];
    }
}