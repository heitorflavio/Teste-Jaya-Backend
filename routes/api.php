<?php

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

Route::group(['prefix' => 'rest', 'middleware' => 'auth:sanctum'], function () {
   Route::controller(App\Http\Controllers\PaymentsController::class)->group(function () {
      Route::get('/payments', 'index');
      Route::post('/payments', 'store');
      Route::get('/payments/{id}', 'show');
      Route::patch('/payment/{id}', 'update');
      Route::delete('/payments/{id}', 'destroy');
   });
});

Route::group(['prefix' => 'rest'], function () {
   Route::controller(App\Http\Controllers\AuthController::class)->group(function () {
      Route::post('/login', 'login');
      Route::post('/logout', 'logout');
      Route::get('/user', 'user');
   });
});
