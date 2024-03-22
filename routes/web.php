<?php

use App\Http\Controllers\HttpController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::post('http_post_request', [HttpController::class, 'store']);

Route::get('/', function () {
    return view('welcome');
});

if (\Illuminate\Support\Facades\App::environment('local')) {

    // App::setLocale('fr');
    $trans = \Illuminate\Support\Facades\Lang::get('auth.failed');
    $trans = __('auth.password');
    $trans = __('auth.throttle', ['seconds' => 5]);
    // current locale
    dump(\Illuminate\Support\Facades\App::currentLocale());
    dump(App::islocale('en'));

    $trans = trans_choice('auth.pants', 2);
    $trans = trans_choice('auth.apples', 2, ['baskets' => 4]);
    $trans = __('auth.welcome', ['name' => 'hicham']);

    dd($trans);

    Route::get('/playground', function () {
        $user = \App\Models\User::factory()->make();
        \Illuminate\Support\Facades\Mail::to($user)
            ->send(new \App\Mail\WelcomeMail($user));
        return null;
    });
}
