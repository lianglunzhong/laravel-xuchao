<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\OrderRequest;
use App\Models\Product;
use App\Models\UserAddress;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use App\Transformers\OrderTransformer;

class OrdersController extends Controller
{
    public function store(OrderRequest $request)
    {
    	$user = $this->user();
        $address = UserAddress::find($request->address_id);
        $remark = $request->remark;
        $items = $request->items;

		// 开启一个数据库事务
        $order = \DB::transaction(function () use ($user, $address, $remark, $items) {
            // 更新此地址的最后使用时间
            $address->update(['last_used_at' => Carbon::now()]);
            // 创建一个订单
            $order   = new Order([
                'address'      => [ // 将地址信息放入订单中
                    'address'       => $address->full_address,
                    'contact_name'  => $address->contact_name,
                    'contact_phone' => $address->contact_phone,
                ],
                'remark'       => $remark,
                'total_amount' => 0,
            ]);
            // 订单关联到当前用户
            $order->user()->associate($user);
            // 写入数据库
            $order->save();

            $totalAmount = 0;
            // 遍历用户提交的商品 product_id
            foreach ($items as $data) {
                $product  = Product::find($data['product_id']);
                // 创建一个 OrderItem 并直接与当前订单关联
    			// $order->items()->make() 等同于 $item = new OrderItem(); $item->order()->associate($order)
                $item = $order->items()->make([
                    'number' => $data['number'],
                    'price'  => $product->price,
                ]);
                $item->product()->associate($product);
                $item->save();
                $totalAmount += $product->price * $data['number'];
                // 用此方法可避免多卖的情况（返回的结果为执行的条数）
                if ($product->decreaseStock($data['number']) <= 0) {
                    return $this->response->errorBadRequest();
                }
            }

            // 更新订单总金额
            $order->update(['total_amount' => $totalAmount]);

            // 将下单的商品从购物车中移除
            $productIds = collect($items)->pluck('product_id')->all();
            $user->cartItems()->whereIn('product_id', $productIds)->delete();

            return $order;
        });
    	
    	return $this->response->item($order, new OrderTransformer());
    }

    public function index(Request $request)
    {
        $orders = $this->user()
                ->orders()
                ->orderBy('created_at', 'desc')
                ->paginate();

        // 在返回的 orders 中获取其关联的 item 和 product 时, 只需要在 transformer 中引入，Dingo Api 会自动帮处理掉 N+ 1 的问题
    	return $this->response->paginator($orders, new OrderTransformer());
    }

    public function show(Order $order, Request $request)
    {
        $this->authorize('own', $order);
        
        return $this->response->item($order, new OrderTransformer);
    }
}
