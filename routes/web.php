<?php

use App\Http\Controllers\HttpController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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

Route::get('/shared/posts/{post}', function (\Illuminate\Http\Request $request, \App\Models\Post $post) {

    return "specially made just for you ;) Post id: {$post->id}";
})->name('shared.post')->middleware('signed');

if (\Illuminate\Support\Facades\App::environment('local')) {

    // Route::get('/shared/videos/{video}', function (\Illuminate\Http\Request $request, $video) {

    //     // if (!$request->hasValidSignature()) {
    //     //     abort(401);
    //     // }

    //     return 'git gud';
    // })->name('share-video')->middleware('signed');

    Route::get('/playground', function () {
        //
        $url = URL::temporarySignedRoute('share-video', now()->addSeconds(30), [
            'video' => 123
        ]);

        return $url;
    });
}
