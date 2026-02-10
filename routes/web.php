<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin'], function () {
    Route::resource('usuarios', 'App\Http\Controllers\Admin\UserController', [
        'parameters' => [
            'usuarios' => 'user',
        ],
        'names' => [
            'index' => 'users',
            'create' => 'users_create',
            'edit' => 'users_edit',
            'store' => 'users_store',
            'destroy' => 'users_destroy',
        ]
    ]);
});
