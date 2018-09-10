<?php

namespace App\Transformers;

use App\Models\OrderItem;
use League\Fractal\TransformerAbstract;

class OrderItemTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['product'];

    public function transform(OrderItem $orderItem)
    {
        return [
            'id' => $orderItem->id,
            'order_id' => $orderItem->order_id,
            'product_id' => $orderItem->product_id,
            'number' => $orderItem->number,
            'price' => $orderItem->price,
        ];
    }

    public function includeProduct(OrderItem $orderItem)
    {
        return $this->item($orderItem->product, new ProductTransformer());
    }
}