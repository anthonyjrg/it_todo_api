<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);

    return ['token' => $token->plainTextToken];
});

Route::post('/user/test', [UserController::class, 'test']);

Route::post('/sanctum/token', [AuthController::class, 'getLoginToken']);

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/user', [UserController::class, 'index']);
    Route::post('/user/logout', [UserController::class, 'destroy']);
    Route::post('/user/tasks', [UserController::class, 'tasks']);
    Route::post('/user/tasks/incomplete', [UserController::class, 'incompleteTasks']);
    Route::post('/user/tasks/complete', [UserController::class, 'completeTasks']);
    Route::post('/task/create', [TaskController::class, 'create']);
    Route::post('/task/status/update', [TaskController::class, 'updateTaskStatus']);

    Route::post('/task/incomplete', [TaskController::class, 'incompleteTask']);
    Route::post('/task/complete', [TaskController::class, 'completeTask']);

    Route::post('/task/counts', [TaskController::class, 'taskListCount']);
});
