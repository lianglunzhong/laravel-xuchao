<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
    	'title', 'description', 'on_sale', 'price', 'stock', 'image', 'label', 'sold_count', 'review_count',
    ];

    protected $casts = [
    	'on_sale' => 'boolean',
    ];
}
