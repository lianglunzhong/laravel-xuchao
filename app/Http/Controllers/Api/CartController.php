<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\AddCartRequest;
use App\Models\CartItem;
use App\Models\Product;
use App\Transformers\CartTransformer;
use Auth;

class CartController extends Controller
{
    public function store(AddCartRequest $request)
    {
    	$user = $this->user();  // Auth::guard('api')->user()

    	$product_id = $request->input('product_id');
    	$number = $request->input('number');

        // 从数据库中查询该商品是否已经在购物车中
        if ($item = $user->cartItems()->where('product_id', $product_id)->first()) {
            // 如果存在则直接叠加商品数量
            $item->update([
                'number' => $item->number + $number,
            ]);
        } else {
            // 否则创建一个新的购物车记录
            $item = new CartItem(['number' => $number]);
            $item->user()->associate($user);
            $item->product()->associate($product_id);
            $item->save();
        }

        $cartItems = $user->cartItems()->get();

        return $this->response->collection($cartItems, new CartTransformer());
    }

    public function index(Request $request)
    {
        $cartItems = $this->user()->cartItems()->get();

        return $this->response->collection($cartItems, new CartTransformer());
    }

    public function destroy(Product $product, Request $request)
    {
    	$this->user()->cartItems()->where('product_id', $product->id)->delete();

    	return $this->response->noContent();
    }
}
