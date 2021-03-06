<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => 'admin.',
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    // 用户
    $router->resource('users', 'UsersController');
    // 角色
    $router->resource('roles', 'RolesController');
    // 权限
    $router->resource('permissions', 'PermissionsController');
    // 话题
    $router->resource('topics', 'TopicsController');
    // 图片上传
    $router->post('upload_image', 'TopicsController@uploadImage');

});
