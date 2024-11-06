<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->to('https://fisicalcoach.com');
});

Route::get('/ws', function () {
    event(new App\Events\TestEvent("Test"));
    // broadcast(new App\Events\TestEvent("Test"));

    // App\Events\TestEvent::dispatch('Profile picutre has been updated');

    return 'Evento enviado';
});

Route::get('/pdf-view', function () {
    return view('subscription_invoice', [
        'data' => 'data??'
    ]);
});
