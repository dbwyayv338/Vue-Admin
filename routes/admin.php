<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group([
    'namespace' => 'App\Http\Controllers\Admin',
    'prefix' => config('admin.prefix'),
    'middleware' => ['auth'],
], function () {

    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('admin.dashboard');

    Route::resource('user', 'UserController');
    Route::resource('role', 'RoleController');
    Route::resource('permission', 'PermissionController');
    Route::resource('menu', 'MenuController')->except(['show']);
    Route::resource('menu.item', 'MenuItemController');

    Route::resource('categories', 'CategoryTypeController')->except(['show']);

    Route::get('edit-account-info', 'UserController@accountInfo')->name('admin.account.info');
    Route::post('edit-account-info', 'UserController@accountInfoStore')->name('admin.account.info.store');
    Route::post('change-password', 'UserController@changePasswordStore')->name('admin.account.password.store');
});
