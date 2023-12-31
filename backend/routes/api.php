<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\PreferencesController;

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

/******* USER ROUTERS ********/
Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/loginUser', [AuthController::class, 'loginUser']);


/******* NEWS ROUTERS ********/
Route::get('/news', [NewsController::class, 'getNews']);
Route::get('/news-live', [NewsController::class, 'getLiveNews']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user-preferences', [PreferencesController::class, 'getUserPreferences']);
    Route::post('/user-preferences', [PreferencesController::class, 'saveUserPreferences']);
});
