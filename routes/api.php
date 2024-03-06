<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Route::controller(AuthController::class)->group(function () {
//     Route::post('login', 'login');
//     Route::post('register', 'register');
//     Route::post('logout', 'logout');
//     Route::post('user', 'user');
// });

Route::get('/users', function (Request $request) {
    dump($request);
    return new JsonResponse([
        'data' => 'aaaaa'
    ]);
});

Route::get('/users/{user}', function (\App\Models\User $user) {
    return new JsonResponse([
        'data' => $user
    ]);
});

Route::post('/users', function () {
    return new JsonResponse([
        'data' => 'posted'
    ]);
});

Route::patch('/users/{user}', function (\App\Models\User $user) {
    return new JsonResponse([
        'data' => 'patched'
    ]);
});

Route::delete('/users/{user}', function (\App\Models\User $user) {
    return new JsonResponse([
        'data' => 'deleted'
    ]);
});
