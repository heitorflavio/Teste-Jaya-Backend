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

Route::group(['prefix' => 'rest'], function () {
   Route::get('/payments', [App\Http\Controllers\PaymentsController::class,'index']);
   Route::post('/payments', [App\Http\Controllers\PaymentsController::class,'store']);
   Route::get('/payments/{id}', [App\Http\Controllers\PaymentsController::class,'show']);
   Route::patch('/payment/{id}', [App\Http\Controllers\PaymentsController::class,'update']);
   Route::delete('/payments/{id}', [App\Http\Controllers\PaymentsController::class,'destroy']);
});
