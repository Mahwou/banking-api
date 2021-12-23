<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\AuthController;
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

Route::prefix('user')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth:sanctum')->group( function () {
    Route::post('/user', [AuthController::class, 'user'])->name('user');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/account', [AccountController::class, 'create'])->name('account');
    Route::post('/transfer', [AccountController::class, 'update'])->name('transfer');
    Route::get('/balance/{id}', [AccountController::class, 'balance'])->name('balance');
    Route::get('/history/{id}', [AccountController::class, 'history'])->name('history');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
