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

Route::group(['prefix' => 'v1'], function () {
   Route::get('/payments', [App\Http\Controllers\PaymentsController::class,'index']);
   Route::post('/payments', [App\Http\Controllers\PaymentsController::class,'store']);
   Route::get('/payments/{payments}', [App\Http\Controllers\PaymentsController::class,'show']);
});

