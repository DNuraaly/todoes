<?php

use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\NoteController;

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
//


Route::group(['prefix' => 'v1'], function ()
{
    Route::group(['middleware' => 'auth:sanctum'], function ()
    {
        Route::group(['prefix' => 'categories'], function ()
        {
            Route::get('', [CategoryController::class, 'index']);
            Route::post('', [CategoryController::class, 'store']);
            Route::get('/{category}', [CategoryController::class, 'show']);
            Route::put('/{id}', [CategoryController::class, 'update']);
            Route::delete('/{id}', [CategoryController::class, 'destroy']);
        });

        Route::group(['prefix' => 'notes'], function ()
        {
            Route::get('', [NoteController::class, 'index']);
            Route::post('', [NoteController::class, 'store']);
            Route::get('/{note}', [NoteController::class, 'show']);
            Route::put('/{id}', [NoteController::class, 'update']);
            Route::delete('/{id}', [NoteController::class, 'destroy']);
        });
        Route::get('logout',[UserController::class, 'logout']);
        Route::get('logout/all',[UserController::class, 'logOutAll']);

        Route::group(['prefix' => 'profile'], function (){
            Route::get('/',[UserController::class, 'index']);
            Route::put('/edit',[UserController::class, 'edit']);
        });
    });
    Route::post('login', [UserController::class, 'login']);
    Route::post('register',[UserController::class, 'createUser']);
});


