<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ColaboradorController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('users', 'App\Http\Controllers\UserController')->only([
    'index', 'store', 'show', 'update', 'destroy'
]);

Route::apiResource('colaboradores', ColaboradorController::class);

Route::fallback(function () {
    return response()->json(['message' => 'Rota não encontrada'], 404);
});