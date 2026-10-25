<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\TransaksiController as AdminTransaksiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;
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
Route::post('/postlogin', [UserController::class, 'postLogin'])->name('post.login');

Route::get('/', [UserController::class, 'home'])->name('index.home');
Route::get('/detail/{id}', [UserController::class, 'detail'])->name('index.detail');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/transaksi/{transaksi}/bukti', [TransaksiController::class, 'bukti'])->name('transaksi.bukti');
});

Route::middleware(['auth', 'customer'])->group(function () {
    Route::get('/keranjang', [UserController::class, 'keranjang'])->name('index.keranjang');
    Route::post('/postkeranjang/{id}', [UserController::class, 'postKeranjang'])->name('post.keranjang');
    Route::put('/keranjang/{id}', [UserController::class, 'updateKeranjang'])->name('update.keranjang');
    Route::delete('/keranjang/{id}', [UserController::class, 'hapusKeranjang'])->name('hapus.keranjang');
    Route::get('/bayar', [UserController::class, 'bayar'])->name('index.bayar');
    Route::post('/prosesbayar', [UserController::class, 'prosesBayar'])->name('proses.bayar');
    Route::get('/summary', [UserController::class, 'summary'])->name('index.summary');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/transaksi', [AdminTransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/{transaksi}', [AdminTransaksiController::class, 'show'])->name('transaksi.show');
    Route::post('/transaksi/{transaksi}/terima', [AdminTransaksiController::class, 'terima'])->name('transaksi.terima');
    Route::post('/transaksi/{transaksi}/tolak', [AdminTransaksiController::class, 'tolak'])->name('transaksi.tolak');

    Route::resource('produk', ProdukController::class)->except('show');
    Route::resource('kategori', KategoriController::class)->except('show');
});
