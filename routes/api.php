<?php

use App\Http\Controllers\api\Apicontroller;
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

Route::prefix('v1')->group(function(){
    
    Route::post('login', [Apicontroller::class,'login'])->name('login');
    Route::post('logout', [Apicontroller::class,'logout']);
    Route::middleware('auth:api')->group(function(){
        Route::resource('/users', Apicontroller::class);
    });

});
