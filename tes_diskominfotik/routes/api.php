<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\api\v1\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// auth
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\api\v1\TaskSharingController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    // task
    Route::post('/task', [TaskController::class, 'store'])->name('store_item');
    Route::get('/task/{id}', [TaskController::class, 'show'])->name('show_item');
    Route::post('/taskIndex', [TaskController::class, 'index'])->name('view_item');
    Route::post('/taskUpdate', [TaskController::class, 'update'])->name('update_item');
    Route::delete('/task/del/{id}', [TaskController::class, 'destroy'])->name('del_item');

    // Berbagi task dengan pengguna lain
    Route::post('/tasks/{task}/share', [TaskSharingController::class, 'shareTask'])->name('share_task');

    // Melihat daftar pengguna yang memiliki akses ke task
    Route::get('/tasks/{task}/users', [TaskSharingController::class, 'getUsersWithAccess'])->name('userListAcces');

    // Menghapus akses pengguna dari task
    Route::post('/tasks/{task}/remove-access', [TaskSharingController::class, 'removeUserAccess'])->name('removeAccess');
});
