<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function (){
    Route::resource('tasks', TaskController::class)
    ->except(['create', 'edit']);
    Route::resource('tasks/{task}/memos', MemoController::class)
    ->except(['create', 'edit']);
    Route::resource('categories', CategoryController::class)
    ->except(['create', 'edit']);
    Route::put('tasks/{task}/complete', [TaskController::class, 'completeTask']);
    Route::put('tasks/{task}/cancel', [TaskController::class, 'cancelTask']);
});
