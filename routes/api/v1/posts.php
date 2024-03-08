<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => [
            // 'auth',
        ],
        'prefix' => 'heyaa',
        'as' => 'posts.',
        'namespace' => "\App\Http\Controllers\API",
    ],
    function () {
        Route::get('posts', 'PostController@index')->name('index');
        Route::get('posts/{post}', 'PostController@show')->name('show');
        Route::post('posts', 'PostController@store')->name('store');
        Route::patch('posts/{post}', 'PostController@update')->name('update');
        Route::delete('posts/{post}', 'PostController@destroy')->name('destroy');
    }
);
