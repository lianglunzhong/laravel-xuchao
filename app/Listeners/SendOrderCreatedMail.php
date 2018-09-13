<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\OrderCreated;
use App\Notifications\OrderCreatedNotification;
use App\Mail\OrderCreatedMail;

class SendOrderCreatedMail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        // 从事件中取出对应的订单
        $order = $event->getOrder();
        // 调用 notify 方法来发送通知
        // $order->user->notify(new OrderCreatedNotification($order));

        \Mail::send(new OrderCreatedMail($order));
    }
}
