<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProgressOfWorkController;
use App\Http\Controllers\ObraController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EtapaController;
use App\Http\Controllers\TarefaController;

Route::post('auth/login', [AuthController::class, 'login']);
Route::group(['prefix' => 'auth'], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('password/send-link', [AuthController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [AuthController::class, 'resetPassword']);
})->middleware('logged');

Route::apiResource('colaboradores', ColaboradorController::class)->middleware('logged');


Route::apiResource('obras', ObraController::class)->middleware('logged');

Route::apiResource('clientes', ClienteController::class)->middleware('logged');

Route::apiResource('etapas', EtapaController::class)->middleware('logged');

Route::apiResource('tarefas', TarefaController::class)->middleware('logged');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'progressOfWork', 'middleware' => 'logged'], function () {
    Route::get('/', 'App\Http\Controllers\ProgressOfWorkController@index');
    Route::get('/{id}', 'App\Http\Controllers\ProgressOfWorkController@show');
    Route::post('/', 'App\Http\Controllers\ProgressOfWorkController@store');
    Route::put('/{id}', 'App\Http\Controllers\ProgressOfWorkController@update');
    Route::delete('/{id}', 'App\Http\Controllers\ProgressOfWorkController@destroy');
});

Route::apiResource('users', 'App\Http\Controllers\UserController')->only([
    'index', 'store', 'show', 'update', 'destroy'
]);

