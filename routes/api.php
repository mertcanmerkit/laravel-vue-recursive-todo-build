<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TodoListController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\LabelController;

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('lists', TodoListController::class);

    Route::prefix('lists/{list}')->group(function () {
        Route::apiResource('tasks', TaskController::class);
    });

    Route::apiResource('labels', LabelController::class);
});
