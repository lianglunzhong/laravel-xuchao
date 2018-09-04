<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
	// 首页
    $router->get('/', 'HomeController@index');
    // 产品
    $router->resource('/products', 'ProductsController', ['except' => ['destroy']]);
});
