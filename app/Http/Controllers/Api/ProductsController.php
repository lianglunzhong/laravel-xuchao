<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Transformers\ProductTransformer;

class ProductsController extends Controller
{
    public function index()
    {
    	$products = Product::query()
    					->where('on_sale', true)
    					->orderBy('created_at', 'desc')
    					->paginate(20);

    	return $this->response->paginator($products, new ProductTransformer());
    }

    public function show(Product $product)
    {
        if (!$product->on_sale) {
            return $this->response->errorBadRequest();
        }

        $product->increment('review_count', 1);
        
        return $this->response->item($product, new ProductTransformer);
    }
}
