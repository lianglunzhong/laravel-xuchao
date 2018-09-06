<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
	'namespace' => 'App\Http\Controllers\Api',
    'middleware' => ['serializer:array', 'bindings'], // serializer:array 减少一次返回数据的嵌套(composer require liyu/dingo-serializer-switch); bindings 把dingo api路由的参数自动绑定到模型上
], function($api) {
	$api->group([
    	'middleware' => 'api.throttle', // 调用频率限制
    	'limit' => config('api.rate_limits.access.limit'), // 次数
    	'expires' => config('api.rate_limits.access.expires'), // 分钟
    ], function($api) {
        // 小程序登录
        $api->post('weapp/authorizations', 'AuthorizationsController@weappStore')
            ->name('api.weapp.authorizations.store');
        // 刷新token
        $api->put('authorizations/current', 'AuthorizationsController@update')
            ->name('api.authorizations.update');

    	/** 游客可以访问的接口 */
    	// 商品列表
    	$api->get('products', 'ProductsController@index')
    		->name('api.products.index');
        // 商品详情
        $api->get('products/{product}', 'ProductsController@show')
            ->name('api.products.show');

        /** 需要 token 验证的接口 */
        $api->group(['middleware' => 'api.auth'], function($api) {
            // 购物车列表
            $api->get('cart', 'CartController@index')
                ->name('api.cart.index');
            // 添加购物车
            $api->post('cart', 'CartController@store')
                ->name('api.cart.store');
            // 删除购物车
            $api->delete('cart/{product}', 'CartController@destroy')
                ->name('api.cart.destroy');
        });
    });
});
