<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashbordController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\EtablissementController;
use App\Http\Controllers\GoogleLocationController;

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

Route::middleware(['auth' ])->group(function () {
    Route::get('/', [DashbordController::class, 'ListeLocalisation'])->name('home');
    Route::get('/Établissements', [GoogleLocationController::class, 'ListeEtablissement'])->name('Établissements');
    Route::get('/fiche/{id}', [EtablissementController::class, 'GetPerfermanceReviews'])->name('fiche');
    Route::get('/logout', [GoogleAuthController::class, 'logout'])->name('logout');
    Route::get('/error', function () {
        return view('error');
    });
});

// Google authentication routes without auth middleware
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
