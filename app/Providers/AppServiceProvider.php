<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 小程序登录
        // 往服务容器中注入一个名为 mini_pro 的单例对象
        $this->app->singleton('mini_pro', function() {
            // 调用 EasyWeChat 来创建一个小程序对象
            return \EasyWeChat::miniProgram();
        });
    }
}
