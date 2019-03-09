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
    $router->post('save-account-amount2', 'CardController@saveAccountAmount2');
    $router->post('import-account-amount2', 'CardController@importAccountAmount2');
    $router->post('import-cards', 'CardController@importCards');
    $router->post('save-import-cards', 'CardController@saveImportCards');
    $router->resource('recharge', RechargeController::class);
    $router->get('account-amount-search', 'CardController@accountAmountSearch');
    $router->post('account-amount-search', 'CardController@saveAccountAmountSearch');
    $router->resource('task', TaskController::class);
    $router->post('task-add', 'TaskController@taskAdd');
    $router->resource('user', UserController::class);
    $router->get('user/task-history/{id}', 'TaskHistoryController@index');
    $router->get('card/card-daily-deduction-history/{id}', 'CardDailyDeductionController@index');
    $router->get('user/send-log/{id}', 'SendLogController@index');
    $router->get('user/user-daily-revenue/{id}', 'UserDailyRevenueController@index');
    $router->resource('withdraw', WithdrawController::class);
    $router->get('invite', 'UserController@invite');
    $router->resource('country', CountryController::class);
    $router->resource('help', HelpController::class);
    $router->get('delete-task-history', 'TaskHistoryController@deletePage');
    $router->post('delete-task-history', 'TaskHistoryController@deleteTaskHistory');

});
