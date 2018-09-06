<?php

namespace App\Http\Requests\Api;
use App\Models\Product;

class AddCartRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!$product = Product::find($value)) {
                        $fail('该商品不存在');
                        return;
                    }
                    if (!$product->on_sale) {
                        $fail('该商品未上架');
                        return;
                    }
                    if ($product->stock === 0) {
                        $fail('该商品已售完');
                    }
                    if ($this->input('number') > 0 && $product->stock < $this->input('number')) {
                        $fail('该商品库存不足');
                        return;
                    }
                }
            ],
            'number' => ['required', 'integer', 'min:1'],
        ];
    }

    public function attributes()
    {
        return [
            'number' => '商品数量',
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => '请选择商品',
        ];
    }
}
