<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/postlogin', [UserController::class, 'postlogin'])->name('post.login');

Route::get('/', [UserController::class, 'home'])->name('index.home');
Route::get('/detail/{id}', [UserController::class, 'detail'])->name('index.detail');


Route::middleware('auth')->group(function () {
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');

    Route::get('/keranjang', [UserController::class, 'keranjang'])->name('index.keranjang');
    Route::post('/postkeranjang/{id}', [UserController::class, 'postKeranjang'])->name('post.keranjang');
    Route::get('/bayar/{id}', [UserController::class, 'bayar'])->name('index.bayar');
    Route::post('/prosesbayar/{id}', [UserController::class, 'prosesBayar'])->name('proses.bayar');
    Route::get('/summary', [UserController::class, 'summary'])->name('index.summary');
});
