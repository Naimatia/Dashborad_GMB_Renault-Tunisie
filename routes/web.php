<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;

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
    return view('dashbord');
});

Route::get('/google-auth', [GoogleAuthController::class, 'loginOrCallback']);

Route::get('/refreshAccessToken', [GoogleAuthController::class, 'refreshAccessToken']);
Route::get('/callGoogleApi', [GoogleAuthController::class, 'callGoogleApi']);


Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
Route::get('/locations', [GoogleAuthController::class, 'callGoogleApi'])->name('locations');
Route::get('/fiche/{id?}',  [GoogleAuthController::class, 'PerfermanceAPI'])->name('fiche');
