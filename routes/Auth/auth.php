<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\Auth\AuthController;

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

Route::controller(AuthController::class)->group(function(){
    Route::post('login', 'login')->name('auth.login');
    Route::post('register', 'register')->name('auth.register');
});


Route::middleware('auth:sanctum')->group( function () {
    Route::get('get-auth-state', [AuthController::class, 'getAuthState'])->name('auth.getAuthState');
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
});
