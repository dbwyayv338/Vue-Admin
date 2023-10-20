<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group([
    'namespace' => 'App\Http\Controllers\Admin',
    'prefix' => config('admin.prefix'),
    'middleware' => ['auth','web'],
], function () {

    Route::get('dashboard', 'DashboardController@index')->name('admin.dashboard');

    Route::resource('user', 'UserController');
    Route::resource('role', 'RoleController');
    Route::resource('permission', 'PermissionController');
    Route::resource('menu', 'MenuController')->except(['show']);
    Route::resource('menu.item', 'MenuItemController');

    Route::resource('category', 'CategoryTypeController')->except(['show']);
    Route::resource('category.item', 'CategoryItemController');

    Route::get('edit-account-info', 'UserController@accountInfo')->name('admin.account.info');
    Route::post('edit-account-info', 'UserController@accountInfoStore')->name('admin.account.info.store');
    Route::post('change-password', 'UserController@changePasswordStore')->name('admin.account.password.store');
    Route::post('switch-language', 'UserController@languageStore')->name('admin.account.language.store');

    Route::post('upload', 'UserController@Upload')->name('admin.upload');
    Route::post('upload-file', 'UserController@UploadFile')->name('admin.upload.file');

    Route::get('template', function () {
        return Inertia::render('Template');
    })->name('admin.template');
});
