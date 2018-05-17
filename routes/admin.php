<?php

Route::group(['middleware' => ['auth:admin']], function ($router) {
    $router->get('/', 'Admin\AdminController@index')->name('admin.index');
    $router->resource('menus', 'Admin\MenuController');
});

// Authentication Routes...
$this->get('login', 'Admin\LoginController@showLoginForm')->name('admin.login');
$this->post('login', 'Admin\LoginController@login');
$this->post('logout', 'Admin\LoginController@logout')->name('admin.logout');
