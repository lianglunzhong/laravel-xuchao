<?php

namespace App\Transformers;

use App\Models\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['items'];

    public function transform(Order $order)
    {
        return [
            'id' => $order->id,
            'no' => $order->no,
            'user_id' => $order->user_id,
            'address' => $order->address,
            'total_amount' => $order->total_amount,
            'remark' => $order->remark,
            'extra' => $order->extra,
            'created_at' => $order->created_at->toDateTimeString(),
        ];
    }

    public function includeItems(Order $order)
    {
        return $this->collection($order->items, new OrderItemTransformer());
    }
}