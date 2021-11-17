<?php

use Canvas\Http\Middleware\Admin;
use Canvas\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::namespace('Auth')->group(function () {
    // Login routes...
    Route::get('login', 'AuthenticatedSessionController@create')->name('canvas.login');
    Route::post('login', 'AuthenticatedSessionController@store');

    // Forgot password routes...
    Route::get('forgot-password', 'PasswordResetLinkController@create')->name('canvas.password.request');
    Route::post('forgot-password', 'PasswordResetLinkController@store')->name('canvas.password.email');

    // Reset password routes...
    Route::get('reset-password/{token}', 'NewPasswordController@create')->name('canvas.password.reset');
    Route::post('reset-password', 'NewPasswordController@store')->name('canvas.password.update');

    // Logout routes...
    Route::get('logout', 'AuthenticatedSessionController@destroy')->name('canvas.logout');
});

Route::middleware([Authenticate::class])->group(function () {
    Route::prefix('api')->group(function () {
        // Stats routes...
        Route::get('stats', 'StatsController');

        // Upload routes...
        Route::prefix('uploads')->group(function () {
            Route::post('/', 'UploadsController@store');
            Route::delete('/', 'UploadsController@destroy');
        });

        // Post routes...
        Route::prefix('posts')->group(function () {
            Route::get('/', 'PostController@index');
            Route::get('create', 'PostController@create');
            Route::get('{id}', 'PostController@show');
            Route::get('{id}/stats', 'PostController@stats');
            Route::post('{id}', 'PostController@store');
            Route::delete('{id}', 'PostController@destroy');
        });

        // Tag routes...
        Route::prefix('tags')->middleware([Admin::class])->group(function () {
            Route::get('/', 'TagController@index');
            Route::get('create', 'TagController@create');
            Route::get('{id}', 'TagController@show');
            Route::get('{id}/posts', 'TagController@posts');
            Route::post('{id}', 'TagController@store');
            Route::delete('{id}', 'TagController@destroy');
        });

        // Topic routes...
        Route::prefix('topics')->middleware([Admin::class])->group(function () {
            Route::get('/', 'TopicController@index');
            Route::get('create', 'TopicController@create');
            Route::get('{id}', 'TopicController@show');
            Route::get('{id}/posts', 'TopicController@posts');
            Route::post('{id}', 'TopicController@store');
            Route::delete('{id}', 'TopicController@destroy');
        });

        // User routes...
        Route::prefix('users')->group(function () {
            Route::get('/', 'UserController@index')->middleware([Admin::class]);
            Route::get('create', 'UserController@create')->middleware([Admin::class]);
            Route::get('{id}', 'UserController@show');
            Route::get('{id}/posts', 'UserController@posts');
            Route::post('{id}', 'UserController@store');
            Route::delete('{id}', 'UserController@destroy')->middleware([Admin::class]);
        });

        // Search routes...
        Route::prefix('search')->group(function () {
            Route::get('posts', 'SearchController@posts');
            Route::get('tags', 'SearchController@tags')->middleware([Admin::class]);
            Route::get('topics', 'SearchController@topics')->middleware([Admin::class]);
            Route::get('users', 'SearchController@users')->middleware([Admin::class]);
        });
    });

    // Catch-all route...
    Route::get('/{view?}', 'ViewController')->where('view', '(.*)')->name('canvas');
});
