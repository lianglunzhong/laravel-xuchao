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
}
