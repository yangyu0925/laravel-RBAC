<?php

Route::group(['middleware' => ['auth:admin']], function ($router) {
    $router->get('/', 'Admin\AdminController@index')->name('admin.index');

    //目录
    $router->resource('menus', 'Admin\MenuController');

    //后台用户
    $router->get('/adminuser/ajaxIndex', 'Admin\AdminUserController@ajaxIndex')->name('adminuser.ajaxIndex');
    $router->resource('adminuser', 'Admin\AdminUserController');

    //权限管理
    $router->get('permission/ajaxIndex', 'Admin\PermissionController@ajaxIndex')->name('permission.ajaxIndex');
    $router->resource('permission', 'Admin\PermissionController');

    //角色管理
    $router->get('role/ajaxIndex','Admin\RoleController@ajaxIndex')->name('role.ajaxIndex');
    $router->resource('role', 'Admin\RoleController');
});

// Authentication Routes...
$this->get('login', 'Admin\LoginController@showLoginForm')->name('admin.login');
$this->post('login', 'Admin\LoginController@login');
$this->post('logout', 'Admin\LoginController@logout')->name('admin.logout');
