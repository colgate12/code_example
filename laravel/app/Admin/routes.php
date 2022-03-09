<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    Route::group([
        'middleware' => 'admin.permission:allow,home',
    ], function ($router) {
        $router->resource('/', HomeController::class);
    });

    Route::group([
        'middleware' => 'admin.permission:allow,products',
    ], function ($router) {
        $router->resource('/products', ProductsController::class);
    });
    Route::group([
        'middleware' => 'admin.permission:allow,products',
    ], function ($router) {
        $router->resource('/products-categories', ProductsCategoriesController::class);
    });
    Route::group([
        'middleware' => 'admin.permission:allow,products',
    ], function ($router) {
        $router->resource('/user', UserController::class);
    });


});
