<?php

namespace App\Transformers;

use App\Models\CartItem;
use League\Fractal\TransformerAbstract;

class CartTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['product'];

    public function transform(CartItem $cart)
    {
        return [
            'id' => $cart->id,
            'user_id' => $cart->user_id,
            'product_id' => $cart->product_id,
            'number' => $cart->number,
        ];
    }

    public function includeProduct(CartItem $cart)
    {
        return $this->item($cart->product, new ProductTransformer());
    }
}