<?php

use Illuminate\Http\Request;
use App\Http\Controllers\api\LoginController;
use App\Http\Controllers\api\KategoriController;
use App\Http\Controllers\api\ActorController;
use App\Http\Controllers\api\GenreController;
use App\Http\Controllers\api\FilmController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route Kategori
// Route::resource('kategori', KategoriController::class);

// // Route Actor
// Route::resource('actor', ActorController::class);

// // Route Genre
// Route::resource('genre', GenreController::class);

// // Route Login
// Route::post('login', [LoginController::class, 'authenticate']);

// // Route Logout
// Route::post('logout', [LoginController::class, 'logout'])
//     ->middleware('auth:sanctum');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('logout', [LoginController::class, 'logout']);
        Route::resource('kategori', KategoriController::class);
        Route::resource('actor', ActorController::class);
        Route::resource('genre', GenreController::class);
        Route::resource('film', FilmController::class);
    });
        // Route login/Register
        Route::post('login', [LoginController::class, 'authenticate']);
        Route::post('register', [LoginController::class, 'register']);
