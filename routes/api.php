<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ObraController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ColaboradorController;


Route::post('auth/login', [AuthController::class, 'login']);
Route::group(['prefix' => 'auth'], function () {
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::get('me', [AuthController::class,'me']);
})->middleware('logged');

Route::apiResource('colaboradores', ColaboradorController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('users', 'App\Http\Controllers\UserController')->only([
    'index', 'store', 'show', 'update', 'destroy'
]);