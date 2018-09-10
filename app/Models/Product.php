<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
    	'title', 'description', 'on_sale', 'price', 'stock', 'image', 'label', 'sold_count', 'review_count',
    ];

    protected $casts = [
    	'on_sale' => 'boolean',
    ];

    public function getImageUrlAttribute()
    {
    	// 如果 image 字段本身就已经是完整的 url 就直接返回
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        return \Storage::disk('public')->url($this->image);
    }

    public function decreaseStock($amount)
    {
        if ($amount < 0) {
            throw new InternalException('减库存不可小于0');
        }

        //$this->newQuery() 方法来获取数据库的查询构造器，ORM 查询构造器的写操作只会返回 true 或者 false 代表 SQL 是否执行成功，而数据库查询构造器的写操作则会返回影响的行数。可以通过返回的影响行数来判断减库存操作是否成功，如果不成功说明商品库存不足。
        return $this->newQuery()
                    ->where('id', $this->id)
                    ->where('stock', '>=', $amount)
                    ->decrement('stock', $amount);
    }

    public function addStock($amount)
    {
        if ($amount < 0) {
            throw new InternalException('加库存不可小于0');
        }

        $this->increment('stock', $amount);
    }
}
