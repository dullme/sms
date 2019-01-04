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
    $router->post('import-cards', 'CardController@importCards');
    $router->post('save-import-cards', 'CardController@saveImportCards');

});
