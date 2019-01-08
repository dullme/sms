<?php

namespace App\Providers;

use App\User;
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
        if (Schema::hasTable('admin_config')) {
            if (class_exists(Config::class)) {
                Config::load();
            }
        }

        /**
         * 手机号验证
         */
        Validator::extend('number20', function ($attribute, $value, $parameters) {
            return preg_match('/^\d{20}$/', $value);
        });

        /**
         * 验证码
         */
        Validator::extend('code', function ($attribute, $value, $parameters) {

            $user = User::where('code', strtoupper($value))->first();

            return $user ? true : false;
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
