<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;
Route::post('/login', [AuthController::class, 'login']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Task management routes
Route::middleware('auth:sanctum')->group(function () {

        // Authentication routes
        Route::middleware('auth:sanctum')->post('/register', [AuthController::class, 'register']);
    // Route::post('/login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
    // 
    Route::middleware('auth:sanctum')->get('/httpTasks', [TaskController::class, 'index_api']);
    Route::middleware('auth:sanctum')->post('/httpTasks', [TaskController::class, 'store_api']);
    Route::middleware('auth:sanctum')->put('/httpTasks/{task}', [TaskController::class, 'update_api']);
    Route::middleware('auth:sanctum')->delete('/httpTasks/{task}', [TaskController::class, 'destroy_api']);
    // Route::middleware('auth:sanctum')->group(function () {
    //     Route::get('/tasks', [TaskController::class, 'index_api']);
    // });
    
    // Route::middleware('auth:sanctum')->get('/tasks', [TaskController::class, 'index']);

});
