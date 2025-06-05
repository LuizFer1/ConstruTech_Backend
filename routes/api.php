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
Route::patch('obras/{obra}/arquivar', [ObraController::class, 'arquivar'])->middleware('logged');

Route::apiResource('clientes', ClienteController::class)->middleware('logged');

Route::apiResource('etapas', EtapaController::class)->middleware('logged');

Route::apiResource('tarefas', TarefaController::class)->middleware('logged');
Route::patch('tarefas/{tarefa}/iniciar', [TarefaController::class, 'iniciarTarefa'])->middleware('logged');
Route::patch('tarefas/{tarefa}/concluir', [TarefaController::class, 'concluirTarefa'])->middleware('logged');
Route::patch('tarefas/{tarefa}/pendente', [TarefaController::class, 'pendente'])->middleware('logged');


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'progressOfWork', 'middleware' => 'logged'], function () {
    Route::get('/', [ProgressOfWorkController::class, 'index']);
    Route::get('/{id}', [ProgressOfWorkController::class, 'show']);
    Route::post('/', [ProgressOfWorkController::class, 'store']);
    Route::put('/{id}', [ProgressOfWorkController::class, 'update']);
    Route::delete('/{id}', [ProgressOfWorkController::class, 'destroy']);
});

Route::apiResource('users', 'App\Http\Controllers\UserController')->only([
    'index',
    'store',
    'show',
    'update',
    'destroy'
]);
