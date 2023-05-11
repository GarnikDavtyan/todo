<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::redirect('/', '/todos');

    Route::get('/todos/give-access', [TodoController::class, 'giveAccessView']);
    Route::post('/todos/give-access', [TodoController::class, 'giveAccess']);

    Route::middleware('rw-access')->group(function () {
        Route::post('/todos/{todo}/tag', [TodoController::class, 'addTag']);
        Route::delete('/todos/{todo}/tag/{tagId}', [TodoController::class, 'deleteTag']);
        Route::delete('/todos/{todo}/image', [TodoController::class, 'deleteImage']);

        Route::resource('todos', TodoController::class)->only(['store', 'update', 'destroy']);
    });

    Route::middleware('access')->group(function () {
        Route::resource('todos', TodoController::class)->only(['index']);
    });
});
