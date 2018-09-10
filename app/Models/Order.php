<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'no',
        'address',
        'total_amount',
        'remark',
        'closed',
        'extra',
    ];

    protected $casts = [
        'closed'    => 'boolean',
        'address'   => 'json',
        'extra'     => 'json',
    ];

    protected $dates = [

    ];

    protected static function boot()
    {
    	parent::boot();

    	// 监听模型创建时间，在写入数据库之前触发
    	static::creating(function ($model) {
    		// 如果模型的 no 字段为空
    		if (!$model->no) {
    			// 调用 findAvailableNo 生成订单流水号
    			$model->no = static::findAvailableNo();
    			// 如果生成失败，则终止创建订单
    			if (!$model->no) {
    				return false;
    			}
    		}
    	});
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function items()
    {
    	return $this->hasMany(OrderItem::class);
    }

    public static function findAvailableNo()
    {
    	// 订单流水号前缀
    	$prefix = date('YmdHis');
    	for ($i = 0; $i < 10; $i++) {
    		// 随机生成 6 位的数字
    		$no = $prefix . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    		// 判断是否已存存在
    		if (!static::query()->where('no', $no)->exists()) {
    			return $no;
    		}
    	}
    	\Log::waring('find order no failed');

    	return false;
    }
}
