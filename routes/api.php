<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/tasks', [\App\Http\Controllers\TaskController::class, 'index']);
Route::post('/tasks', [\App\Http\Controllers\TaskController::class, 'store']);