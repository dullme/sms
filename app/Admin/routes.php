<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('card', CardController::class);
    $router->get('add-account', 'CardController@addAccount');
    $router->get('add-account-amount', 'CardController@addAccountAmount');
    $router->post('add-account-amount', 'CardController@saveAccountAmount');
    $router->post('import-cards', 'CardController@importCards');
    $router->post('save-import-cards', 'CardController@saveImportCards');
    $router->resource('recharge', RechargeController::class);
    $router->get('account-amount-search', 'CardController@accountAmountSearch');
    $router->post('account-amount-search', 'CardController@saveAccountAmountSearch');
    $router->resource('task', TaskController::class);

});
