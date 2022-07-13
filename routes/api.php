<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, ForumCommentController, ForumController, RegisterController};

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

Route::middleware(['api'])->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [RegisterController::class, 'register']);
        Route::post('logout', [AuthController::class, 'refresh']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('me', [AuthController::class, 'me']);
    });

    Route::get('forum/tag/{tag}', [ForumController::class, 'filterTag']);

    Route::apiResource('forums', ForumController::class);
    Route::apiResource('forums.comments', ForumCommentController::class);
});
