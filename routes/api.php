<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// controller
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NewsLogsController;

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

Route::get('/unauthorized', function () {
    return response()->json([
        'success' => false,
        'message' => "Unauthorized"
    ], 403);
})->name('unauthorized');

/**
 * Dengan menggunakan apiResource maka laravel akan membuat 
 * routing dengan kebutuhan CRUD secara otmatis, seperti dibawah :
 * 
 * Route::get('/segment', [Controller::class, 'index']);
 * Route::get('/segment/{id}', [Controller::class, 'show']);
 * Route::post('/segment', [Controller::class, 'store']);
 * Route::put('/segment', [Controller::class, 'update']);
 * Route::delete('/segment', [Controller::class, 'destroy']);
 */

Route::apiResource('/users', UsersController::class);

// auth group
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/comments', CommentController::class);
    Route::apiResource('/news', NewsController::class);

    Route::get('/newslogs', [NewsLogsController::class, 'index']);
    Route::get('/newslogs/{id}', [NewsLogsController::class, 'show']);
});
