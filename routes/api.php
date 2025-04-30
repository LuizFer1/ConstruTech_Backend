<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProgressOfWorkController;
use App\Http\Controllers\ObraController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AuthController;

Route::post('auth/login', [AuthController::class, 'login']);
Route::group(['prefix' => 'auth'], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
})->middleware('logged');

Route::apiResource('colaboradores', ColaboradorController::class);

Route::apiResource('obras', ObraController::class)->middleware('logged');

Route::apiResource('clientes', ClienteController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'progressOfWork'], function () {
    Route::get('/', 'App\Http\Controllers\ProgressOfWorkController@index');
    Route::get('/{id}', 'App\Http\Controllers\ProgressOfWorkController@show');
    Route::post('/', 'App\Http\Controllers\ProgressOfWorkController@store');
    Route::put('/{id}', 'App\Http\Controllers\ProgressOfWorkController@update');
    Route::delete('/{id}', 'App\Http\Controllers\ProgressOfWorkController@destroy');
});

Route::apiResource('users', 'App\Http\Controllers\UserController')->only([
    'index', 'store', 'show', 'update', 'destroy'
]);
