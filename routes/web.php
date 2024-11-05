
<?php

use App\Models\Lapangan;
use App\Models\Penyewaan;
use App\Models\Transaksi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\adminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PenyewaanController;
use App\Http\Controllers\TransaksiController;

// Route untuk menampilkan halaman jadwal dengan data lapangan dan tanggal
//::get('/jadwal', [PenyewaanController::class, 'getAvailableSlots'])->name('jadwal');

Route::middleware(AdminMiddleware::class)->group(function () {

    Route::get('/fetch-rentals', [adminController::class, 'fetchRentals'])->name('fetch.rentals');

    Route::get('/admin', [adminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/admin', [adminController::class, 'admin'])->name('admin.admin');
    Route::get('/admin/users', [adminController::class, 'users'])->name('admin.users');
    Route::post('/admin/users/store', [adminController::class, 'storeUser'])->name('admin.users.store');
    Route::put('/admin/users/update', [adminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/destroy', [adminController::class, 'destroyUser'])->name('admin.users.destroy');
    // Data Lapangan
    Route::get('/admin/lapangan', [adminController::class, 'lapangan'])->name('admin.lapangan');
    Route::post('/admin/lapangan/store', [admincontroller::class, 'storeLapangan'])->name('admin.lapangan.store');
    Route::put('/admin/lapangan/update', [admincontroller::class, 'updateLapangan'])->name('admin.lapangan.update');
    Route::delete('/admin/lapangan/destroy', [AdminController::class, 'destroyLapangan'])->name('admin.lapangan.destroy');
    // Penyewaan
    Route::get('/admin/konfirmasi', [adminController::class, 'konfirmasi'])->name('admin.konfirmasi');
    
    //aksi
    Route::get('/admin/transaksi/konfirmasi/{no_trans}', [TransaksiController::class, 'konfirmasi'])->name('transaksi.konfirmasi');
    Route::get('/admin/transaksi/konfirmasi', [TransaksiController::class, 'konfirmasiSemua'])->name('transaksi.konfirmasiSemua');
    Route::get('/admin/transaksi/batal/{no_trans}', [TransaksiController::class, 'batal'])->name('transaksi.batal');
    Route::get('/admin/transaksi/edit/{no_trans}', [TransaksiController::class, 'editTrans'])->name('transaksi.edit');

    // transaksi
    Route::get('/admin/transaksi/historyTable', [adminController::class, 'transaksi'])->name('admin.historyTable');
    Route::get('/admin/transaksi/all', [adminController::class, 'transaksi'])->name('admin.transaksi.all');
    Route::get('/admin/transaksi/daily', [adminController::class, 'transaksi'])->name('admin.transaksi.daily');
    Route::get('/admin/transaksi/weekly', [adminController::class, 'transaksi'])->name('admin.transaksi.weekly');
    Route::get('/admin/transaksi/monthly', [adminController::class, 'transaksi'])->name('admin.transaksi.monthly');
    Route::get('/admin/transaksi/yearly', [adminController::class, 'transaksi'])->name('admin.transaksi.yearly');
    Route::get('/admin/transaksi/export', [adminController::class, 'exportPDF'])->name('transaksi.export-pdf');
    Route::get('/admin/penyewaan/edit', [adminController::class, 'editpenyewaan'])->name('admin.penyewaan.edit');

    
});


// Group route dengan middleware 'auth'
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect('/home');
    });
    
    Route::get('/home', function () {
        return view('dashboard', ['lapangan' => Lapangan::all()]);
    })->name('dashboard');



    Route::get('/penyewaan', [TransaksiController::class, 'index'])->name('penyewaan');
    Route::get('/checkout', [MidtransController::class, 'checkout'])->name('midtrans');


    Route::get('/reservasi', [PenyewaanController::class, 'index'])->name('reservasi');
    Route::get('/update-slots', [PenyewaanController::class, 'updateSlots'])->name('update.slots');

    Route::post('/keranjang.store', [PenyewaanController::class, 'store'])->name('penyewaan.store');

    Route::post('/cek-ketersediaan', [PenyewaanController::class, 'cekKetersediaan'])->name('cek.ketersediaan');

    // routes/web.php
    Route::delete('/keranjang/{keranjang}', [KeranjangController::class, 'destroy'])->name('keranjang.destroy');
    Route::delete('/keranjang', [KeranjangController::class, 'destroy_all'])->name('keranjang.destroy.all');
    Route::post('/keranjang/tambah', [KeranjangController::class, 'store'])->name('keranjang.tambah');
    Route::post('/slot-data', [KeranjangController::class, 'data'])->name('slot.data');

    // Route untuk menambahkan penyewaan ke keranjang

    Route::post('/penyewaan/pembayaran', [TransaksiController::class, 'pembayaran'])->name('penyewaan.pembayaran');
   

    // Route untuk melanjutkan ke pembayaran
    Route::get('/pembayaran', function () {
        return view('pembayaran.index');
    })->name('pembayaran');

    // Route untuk profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
