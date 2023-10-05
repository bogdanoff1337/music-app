<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotifyController;
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

Route::get('/', function () {
    return view('app');
});



Route::get('/spotify/redirect', [SpotifyController::class, 'redirectToSpotify']);
Route::get('/callback', [SpotifyController::class, 'handleCallback']);
