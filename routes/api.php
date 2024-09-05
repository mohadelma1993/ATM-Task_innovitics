<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ATMController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

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



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::middleware('auth:api')->group(function () {
    Route::post('/deposit', [ATMController::class, 'deposit'])->name('atm.deposit');
    Route::post('/withdraw', [ATMController::class, 'withdraw'])->name('atm.withdraw');
    Route::get('/balance', [ATMController::class, 'balance'])->name('atm.balance');
    Route::get('/transactions', [ATMController::class, 'transactions'])->name('atm.transactions');
});
Route::post('/login',[AuthController::Class,'login'])->name('login');

Route::post('admin/login',[AdminController::Class,'adminLogin'])->name('adminLogin');


Route::middleware(['auth:api', 'can:isAdmin'])->group(function () {
    Route::post('/admin/create-user', [AdminController::class, 'createUser'])->name('admin.createUser');
    Route::get('/admin/transactions', [AdminController::class, 'getAllTransactions'])->name('admin.getAllTransactions');
});
