<?php

namespace App\Providers;

use Validator;
use Encore\Admin\Config\Config;
use Illuminate\Support\Facades\Schema;
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
        if(Schema::hasTable('admin_config')){
            if (class_exists(Config::class)) {
                Config::load();
            }
        }

        /**
         * 手机号验证
         */
        Validator::extend('number20', function($attribute, $value, $parameters) {
            return preg_match('/^\d{20}$/', $value);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
