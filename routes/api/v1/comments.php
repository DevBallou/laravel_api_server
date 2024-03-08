<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => [
            // 'auth',
        ],
        'prefix' => 'heyaa',
        'name' => 'comments.',
        'namespace' => "\App\Http\Controllers\API",
    ],
    function () {
        Route::get('comments', 'CommentController@index')->name('index');
        Route::get('comments/{comment}', 'CommentController@show')->name('show');
        Route::post('comments', 'CommentController@store')->name('store');
        Route::patch('comments/{comment}', 'CommentController@update')->name('update');
        Route::delete('comments/{comment}', 'CommentController@destroy')->name('destroy');
    }
);
