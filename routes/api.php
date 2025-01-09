<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
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


Route::get('get-districts', [HomeController::class, 'getCities']);

Route::get('get-correct-seconds', [\App\Http\Controllers\BreakLogsController::class, 'getCorrectSeconds'])->name('getCorrectSeconds');

Route::post('/user-store', [UserController::class, 'userStore'])->name('user_store');

Route::post('delete-product', [UserController::class, 'deleteProductImage']);

Route::post('change/status', [UserController::class, 'changeStatus'])->name('change_status');
