<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
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
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group( ['middleware' => ["auth:sanctum"]], function(){
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::put('/update', [AuthController::class, 'completeInformation']);
    Route::put('/index', [FavoriteController::class, 'index']);
    Route::post('/create', [FavoriteController::class, 'create']);
    Route::put('/show', [FavoriteController::class, 'show']);
    Route::get('/store', [FavoriteController::class, 'store']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

