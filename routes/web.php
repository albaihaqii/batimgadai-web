<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LelangController;

// Landing Page
Route::get('/', fn() => view('frontend.index'))->name('home');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Public
Route::get('/loker/{kode_loker}', [App\Http\Controllers\LockerController::class, 'scan'])->name('loker.scan');
Route::get('/verify/{qr_token}', [App\Http\Controllers\GadaiController::class, 'verify'])->name('sbg.verify');
Route::post('/midtrans/callback/perpanjangan', [App\Http\Controllers\PerpanjanganController::class, 'callback'])->name('midtrans.callback.perpanjangan');
Route::post('/midtrans/callback/pelunasan', [App\Http\Controllers\PelunasanController::class, 'callback'])->name('midtrans.callback.pelunasan');
Route::post('/midtrans/callback', [App\Http\Controllers\MidtransController::class, 'callback'])->name('midtrans.callback');

Route::get('/api/preview-jasa-rate', function (\Illuminate\Http\Request $request) {
    $nilai = (int) $request->get('nilai', 0);
    $tipe  = $request->get('tipe', 'umum');
    $data  = \App\Helpers\HitungBiayaHelper::previewRate($nilai, $tipe);
    return response()->json($data);
});

// API Mobile
Route::get('/api/mobile/notifikasi', [App\Http\Controllers\Api\MobileNotificationController::class, 'index']);
Route::post('/api/mobile/notifikasi/{id}/read', [App\Http\Controllers\Api\MobileNotificationController::class, 'markRead']);
Route::get('/api/mobile/banners', [App\Http\Controllers\BannerController::class, 'apiBanners']);
Route::get('/api/simulasi/options', [App\Http\Controllers\SimulasiController::class, 'apiOptions']);
Route::post('/api/simulasi/hitung', [App\Http\Controllers\SimulasiController::class, 'apiHitung']);

// Superadmin
Route::prefix('superadmin')
    ->name('superadmin.')
    ->middleware(['auth', 'role:superadmin'])
    ->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::get('/password', [App\Http\Controllers\ProfileController::class, 'showPassword'])->name('password');
        Route::put('/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password.update');

        Route::get('/nasabah', [App\Http\Controllers\CustomerController::class, 'index'])->name('nasabah');
        Route::get('/nasabah/tambah', [App\Http\Controllers\CustomerController::class, 'create'])->name('nasabah.create');
        Route::post('/nasabah', [App\Http\Controllers\CustomerController::class, 'store'])->name('nasabah.store');
        Route::get('/nasabah/{customer}/edit', [App\Http\Controllers\CustomerController::class, 'edit'])->name('nasabah.edit');
        Route::put('/nasabah/{customer}', [App\Http\Controllers\CustomerController::class, 'update'])->name('nasabah.update');
        Route::delete('/nasabah/{customer}', [App\Http\Controllers\CustomerController::class, 'destroy'])->name('nasabah.destroy');

        Route::get('/pimpinan', [App\Http\Controllers\AdminController::class, 'index'])->name('pimpinan');
        Route::get('/pimpinan/tambah', [App\Http\Controllers\AdminController::class, 'create'])->name('pimpinan.create');
        Route::post('/pimpinan', [App\Http\Controllers\AdminController::class, 'store'])->name('pimpinan.store');
        Route::get('/pimpinan/{admin}/edit', [App\Http\Controllers\AdminController::class, 'edit'])->name('pimpinan.edit');
        Route::put('/pimpinan/{admin}', [App\Http\Controllers\AdminController::class, 'update'])->name('pimpinan.update');
        Route::delete('/pimpinan/{admin}', [App\Http\Controllers\AdminController::class, 'destroy'])->name('pimpinan.destroy');

        Route::get('/petugas', [App\Http\Controllers\OfficerController::class, 'index'])->name('petugas');
        Route::get('/petugas/tambah', [App\Http\Controllers\OfficerController::class, 'create'])->name('petugas.create');
        Route::post('/petugas', [App\Http\Controllers\OfficerController::class, 'store'])->name('petugas.store');
        Route::get('/petugas/{officer}/edit', [App\Http\Controllers\OfficerController::class, 'edit'])->name('petugas.edit');
        Route::put('/petugas/{officer}', [App\Http\Controllers\OfficerController::class, 'update'])->name('petugas.update');
        Route::delete('/petugas/{officer}', [App\Http\Controllers\OfficerController::class, 'destroy'])->name('petugas.destroy');

        Route::get('/cabang', [App\Http\Controllers\BranchController::class, 'index'])->name('cabang');
        Route::get('/cabang/tambah', [App\Http\Controllers\BranchController::class, 'create'])->name('cabang.create');
        Route::post('/cabang', [App\Http\Controllers\BranchController::class, 'store'])->name('cabang.store');
        Route::get('/cabang/{branch}/edit', [App\Http\Controllers\BranchController::class, 'edit'])->name('cabang.edit');
        Route::put('/cabang/{branch}', [App\Http\Controllers\BranchController::class, 'update'])->name('cabang.update');
        Route::delete('/cabang/{branch}', [App\Http\Controllers\BranchController::class, 'destroy'])->name('cabang.destroy');

        Route::get('/loker', [App\Http\Controllers\LockerController::class, 'index'])->name('loker');
        Route::get('/loker/tambah', [App\Http\Controllers\LockerController::class, 'create'])->name('loker.create');
        Route::post('/loker', [App\Http\Controllers\LockerController::class, 'store'])->name('loker.store');
        Route::delete('/loker/{locker}', [App\Http\Controllers\LockerController::class, 'destroy'])->name('loker.destroy');

        Route::get('/jasa-rate', [App\Http\Controllers\JasaRateController::class, 'index'])->name('jasa-rate');
        Route::get('/jasa-rate/tambah', [App\Http\Controllers\JasaRateController::class, 'create'])->name('jasa-rate.create');
        Route::post('/jasa-rate', [App\Http\Controllers\JasaRateController::class, 'store'])->name('jasa-rate.store');
        Route::get('/jasa-rate/{jasaRate}/edit', [App\Http\Controllers\JasaRateController::class, 'edit'])->name('jasa-rate.edit');
        Route::put('/jasa-rate/{jasaRate}', [App\Http\Controllers\JasaRateController::class, 'update'])->name('jasa-rate.update');
        Route::delete('/jasa-rate/{jasaRate}', [App\Http\Controllers\JasaRateController::class, 'destroy'])->name('jasa-rate.destroy');

        Route::get('/transaksi/gadai', [App\Http\Controllers\GadaiController::class, 'index'])->name('transaksi.gadai');
        Route::get('/transaksi/gadai/tambah', [App\Http\Controllers\GadaiController::class, 'create'])->name('transaksi.gadai.create');
        Route::post('/transaksi/gadai', [App\Http\Controllers\GadaiController::class, 'store'])->name('transaksi.gadai.store');
        Route::get('/transaksi/gadai/{gadai}', [App\Http\Controllers\GadaiController::class, 'show'])->name('transaksi.gadai.show');
        Route::delete('/transaksi/gadai/{gadai}', [App\Http\Controllers\GadaiController::class, 'destroy'])->name('transaksi.gadai.destroy');
        Route::get('/transaksi/gadai/{gadai}/sbg', [App\Http\Controllers\GadaiController::class, 'downloadSbg'])->name('transaksi.gadai.sbg');
        Route::post('/transaksi/gadai/{gadai}/tambah-pinjaman', [App\Http\Controllers\GadaiController::class, 'tambahPinjaman'])->name('transaksi.gadai.tambah-pinjaman');

        Route::get('/approval/gadai', [App\Http\Controllers\ApprovalController::class, 'index'])->name('approval.gadai');
        Route::get('/approval/gadai/{gadai}', [App\Http\Controllers\ApprovalController::class, 'show'])->name('approval.gadai.show');
        Route::post('/approval/gadai/{gadai}', [App\Http\Controllers\ApprovalController::class, 'proses'])->name('approval.gadai.proses');

        Route::get('/booking/kunjungan', [App\Http\Controllers\BookingController::class, 'index'])->name('booking.kunjungan');
        Route::get('/booking/kunjungan/{id}', [App\Http\Controllers\BookingController::class, 'show'])->name('booking.kunjungan.show');
        Route::post('/booking/kunjungan/{id}/proses', [App\Http\Controllers\BookingController::class, 'proses'])->name('booking.kunjungan.proses');
        Route::post('/booking/kunjungan/{id}/selesai', [App\Http\Controllers\BookingController::class, 'selesai'])->name('booking.kunjungan.selesai');

        Route::get('/transaksi/perpanjangan', [App\Http\Controllers\PerpanjanganController::class, 'index'])->name('transaksi.perpanjangan');
        Route::get('/transaksi/perpanjangan/proses', [App\Http\Controllers\PerpanjanganController::class, 'create'])->name('transaksi.perpanjangan.create');
        Route::post('/transaksi/perpanjangan', [App\Http\Controllers\PerpanjanganController::class, 'store'])->name('transaksi.perpanjangan.store');
        Route::get('/transaksi/perpanjangan/{perpanjangan}', [App\Http\Controllers\PerpanjanganController::class, 'show'])->name('transaksi.perpanjangan.show');
        Route::post('/transaksi/perpanjangan/{perpanjangan}/retry', [App\Http\Controllers\PerpanjanganController::class, 'retry'])->name('transaksi.perpanjangan.retry');

        Route::get('/transaksi/pelunasan', [App\Http\Controllers\PelunasanController::class, 'index'])->name('transaksi.pelunasan');
        Route::get('/transaksi/pelunasan/proses', [App\Http\Controllers\PelunasanController::class, 'create'])->name('transaksi.pelunasan.create');
        Route::post('/transaksi/pelunasan', [App\Http\Controllers\PelunasanController::class, 'store'])->name('transaksi.pelunasan.store');
        Route::get('/transaksi/pelunasan/{pelunasan}', [App\Http\Controllers\PelunasanController::class, 'show'])->name('transaksi.pelunasan.show');
        Route::post('/transaksi/pelunasan/{pelunasan}/retry', [App\Http\Controllers\PelunasanController::class, 'retry'])->name('transaksi.pelunasan.retry');

        Route::get('/laporan/harian', [App\Http\Controllers\ReportController::class, 'harian'])->name('laporan.harian');
        Route::get('/laporan/mingguan', [App\Http\Controllers\ReportController::class, 'mingguan'])->name('laporan.mingguan');
        Route::get('/laporan/bulanan', [App\Http\Controllers\ReportController::class, 'bulanan'])->name('laporan.bulanan');
        Route::get('/laporan/harian/export', [App\Http\Controllers\ReportController::class, 'exportHarian'])->name('laporan.harian.export');
        Route::get('/laporan/mingguan/export', [App\Http\Controllers\ReportController::class, 'exportMingguan'])->name('laporan.mingguan.export');
        Route::get('/laporan/bulanan/export', [App\Http\Controllers\ReportController::class, 'exportBulanan'])->name('laporan.bulanan.export');

        Route::get('/notifikasi', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifikasi');
        Route::post('/notifikasi/read-all', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifikasi.read-all');
        Route::post('/notifikasi/{id}/read', [App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifikasi.read');

        Route::get('/lelang', [LelangController::class, 'index'])->name('lelang');
        Route::get('/lelang/{id}', [LelangController::class, 'show'])->name('lelang.show');
        Route::post('/lelang/{id}/proses', [LelangController::class, 'proses'])->name('lelang.proses');
        Route::post('/lelang/{id}/batal', [LelangController::class, 'batal'])->name('lelang.batal');

        Route::get('/banner/landing', [App\Http\Controllers\BannerController::class, 'indexLanding'])->name('banner.landing');
        Route::get('/banner/landing/tambah', [App\Http\Controllers\BannerController::class, 'createLanding'])->name('banner.landing.create');
        Route::post('/banner/landing', [App\Http\Controllers\BannerController::class, 'storeLanding'])->name('banner.landing.store');
        Route::get('/banner/landing/{id}/edit', [App\Http\Controllers\BannerController::class, 'editLanding'])->name('banner.landing.edit');
        Route::put('/banner/landing/{id}', [App\Http\Controllers\BannerController::class, 'updateLanding'])->name('banner.landing.update');
        Route::delete('/banner/{id}', [App\Http\Controllers\BannerController::class, 'destroy'])->name('banner.destroy');
        Route::post('/banner/{id}/toggle', [App\Http\Controllers\BannerController::class, 'toggle'])->name('banner.toggle');

        Route::get('/banner/mobile', [App\Http\Controllers\BannerController::class, 'indexMobile'])->name('banner.mobile');
        Route::get('/banner/mobile/tambah', [App\Http\Controllers\BannerController::class, 'createMobile'])->name('banner.mobile.create');
        Route::post('/banner/mobile', [App\Http\Controllers\BannerController::class, 'storeMobile'])->name('banner.mobile.store');
        Route::get('/banner/mobile/{id}/edit', [App\Http\Controllers\BannerController::class, 'editMobile'])->name('banner.mobile.edit');
        Route::put('/banner/mobile/{id}', [App\Http\Controllers\BannerController::class, 'updateMobile'])->name('banner.mobile.update');

        Route::get('/simulasi', [App\Http\Controllers\SimulasiController::class, 'index'])->name('simulasi');
        Route::post('/simulasi/master/{kategori}', [App\Http\Controllers\SimulasiController::class, 'updateMaster'])->name('simulasi.master.update');
        Route::post('/simulasi/kecacatan', [App\Http\Controllers\SimulasiController::class, 'storeKecacatan'])->name('simulasi.kecacatan.store');
        Route::put('/simulasi/kecacatan/{id}', [App\Http\Controllers\SimulasiController::class, 'updateKecacatan'])->name('simulasi.kecacatan.update');
        Route::delete('/simulasi/kecacatan/{id}', [App\Http\Controllers\SimulasiController::class, 'destroyKecacatan'])->name('simulasi.kecacatan.destroy');
        Route::post('/simulasi/kelengkapan', [App\Http\Controllers\SimulasiController::class, 'storeKelengkapan'])->name('simulasi.kelengkapan.store');
        Route::put('/simulasi/kelengkapan/{id}', [App\Http\Controllers\SimulasiController::class, 'updateKelengkapan'])->name('simulasi.kelengkapan.update');
        Route::delete('/simulasi/kelengkapan/{id}', [App\Http\Controllers\SimulasiController::class, 'destroyKelengkapan'])->name('simulasi.kelengkapan.destroy');
    });

// Admin
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::get('/password', [App\Http\Controllers\ProfileController::class, 'showPassword'])->name('password');
        Route::put('/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password.update');

        Route::get('/nasabah', [App\Http\Controllers\CustomerController::class, 'index'])->name('nasabah');
        Route::get('/nasabah/tambah', [App\Http\Controllers\CustomerController::class, 'create'])->name('nasabah.create');
        Route::post('/nasabah', [App\Http\Controllers\CustomerController::class, 'store'])->name('nasabah.store');
        Route::get('/nasabah/{customer}/edit', [App\Http\Controllers\CustomerController::class, 'edit'])->name('nasabah.edit');
        Route::put('/nasabah/{customer}', [App\Http\Controllers\CustomerController::class, 'update'])->name('nasabah.update');
        Route::delete('/nasabah/{customer}', [App\Http\Controllers\CustomerController::class, 'destroy'])->name('nasabah.destroy');

        Route::get('/petugas', [App\Http\Controllers\OfficerController::class, 'index'])->name('petugas');
        Route::get('/petugas/tambah', [App\Http\Controllers\OfficerController::class, 'create'])->name('petugas.create');
        Route::post('/petugas', [App\Http\Controllers\OfficerController::class, 'store'])->name('petugas.store');
        Route::get('/petugas/{officer}/edit', [App\Http\Controllers\OfficerController::class, 'edit'])->name('petugas.edit');
        Route::put('/petugas/{officer}', [App\Http\Controllers\OfficerController::class, 'update'])->name('petugas.update');
        Route::delete('/petugas/{officer}', [App\Http\Controllers\OfficerController::class, 'destroy'])->name('petugas.destroy');

        Route::get('/loker', [App\Http\Controllers\LockerController::class, 'index'])->name('loker');
        Route::get('/loker/tambah', [App\Http\Controllers\LockerController::class, 'create'])->name('loker.create');
        Route::post('/loker', [App\Http\Controllers\LockerController::class, 'store'])->name('loker.store');
        Route::delete('/loker/{locker}', [App\Http\Controllers\LockerController::class, 'destroy'])->name('loker.destroy');

        Route::get('/transaksi/gadai', [App\Http\Controllers\GadaiController::class, 'index'])->name('transaksi.gadai');
        Route::get('/transaksi/gadai/{gadai}', [App\Http\Controllers\GadaiController::class, 'show'])->name('transaksi.gadai.show');
        Route::get('/transaksi/gadai/{gadai}/sbg', [App\Http\Controllers\GadaiController::class, 'downloadSbg'])->name('transaksi.gadai.sbg');

        Route::get('/approval/gadai', [App\Http\Controllers\ApprovalController::class, 'index'])->name('approval.gadai');
        Route::get('/approval/gadai/{gadai}', [App\Http\Controllers\ApprovalController::class, 'show'])->name('approval.gadai.show');
        Route::post('/approval/gadai/{gadai}', [App\Http\Controllers\ApprovalController::class, 'proses'])->name('approval.gadai.proses');

        Route::get('/booking/kunjungan', [App\Http\Controllers\BookingController::class, 'index'])->name('booking.kunjungan');
        Route::get('/booking/kunjungan/{id}', [App\Http\Controllers\BookingController::class, 'show'])->name('booking.kunjungan.show');
        Route::post('/booking/kunjungan/{id}/proses', [App\Http\Controllers\BookingController::class, 'proses'])->name('booking.kunjungan.proses');
        Route::post('/booking/kunjungan/{id}/selesai', [App\Http\Controllers\BookingController::class, 'selesai'])->name('booking.kunjungan.selesai');

        Route::get('/transaksi/perpanjangan', [App\Http\Controllers\PerpanjanganController::class, 'index'])->name('transaksi.perpanjangan');
        Route::get('/transaksi/perpanjangan/{perpanjangan}', [App\Http\Controllers\PerpanjanganController::class, 'show'])->name('transaksi.perpanjangan.show');
        Route::get('/transaksi/pelunasan', [App\Http\Controllers\PelunasanController::class, 'index'])->name('transaksi.pelunasan');
        Route::get('/transaksi/pelunasan/{pelunasan}', [App\Http\Controllers\PelunasanController::class, 'show'])->name('transaksi.pelunasan.show');

        Route::get('/laporan/harian', [App\Http\Controllers\ReportController::class, 'harian'])->name('laporan.harian');
        Route::get('/laporan/mingguan', [App\Http\Controllers\ReportController::class, 'mingguan'])->name('laporan.mingguan');
        Route::get('/laporan/bulanan', [App\Http\Controllers\ReportController::class, 'bulanan'])->name('laporan.bulanan');
        Route::get('/laporan/harian/export', [App\Http\Controllers\ReportController::class, 'exportHarian'])->name('laporan.harian.export');
        Route::get('/laporan/mingguan/export', [App\Http\Controllers\ReportController::class, 'exportMingguan'])->name('laporan.mingguan.export');
        Route::get('/laporan/bulanan/export', [App\Http\Controllers\ReportController::class, 'exportBulanan'])->name('laporan.bulanan.export');

        Route::get('/notifikasi', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifikasi');
        Route::post('/notifikasi/read-all', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifikasi.read-all');
        Route::post('/notifikasi/{id}/read', [App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifikasi.read');

        Route::get('/banner/mobile', [App\Http\Controllers\BannerController::class, 'indexMobile'])->name('banner.mobile');
        Route::get('/banner/mobile/tambah', [App\Http\Controllers\BannerController::class, 'createMobile'])->name('banner.mobile.create');
        Route::post('/banner/mobile', [App\Http\Controllers\BannerController::class, 'storeMobile'])->name('banner.mobile.store');
        Route::get('/banner/mobile/{id}/edit', [App\Http\Controllers\BannerController::class, 'editMobile'])->name('banner.mobile.edit');
        Route::put('/banner/mobile/{id}', [App\Http\Controllers\BannerController::class, 'updateMobile'])->name('banner.mobile.update');
        Route::delete('/banner/{id}', [App\Http\Controllers\BannerController::class, 'destroy'])->name('banner.destroy');
        Route::post('/banner/{id}/toggle', [App\Http\Controllers\BannerController::class, 'toggle'])->name('banner.toggle');
    });

// Officer
Route::prefix('officer')
    ->name('officer.')
    ->middleware(['auth', 'role:officer'])
    ->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::get('/password', [App\Http\Controllers\ProfileController::class, 'showPassword'])->name('password');
        Route::put('/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password.update');

        Route::get('/nasabah', [App\Http\Controllers\CustomerController::class, 'index'])->name('nasabah');
        Route::get('/nasabah/tambah', [App\Http\Controllers\CustomerController::class, 'create'])->name('nasabah.create');
        Route::post('/nasabah', [App\Http\Controllers\CustomerController::class, 'store'])->name('nasabah.store');
        Route::get('/nasabah/{customer}/edit', [App\Http\Controllers\CustomerController::class, 'edit'])->name('nasabah.edit');
        Route::put('/nasabah/{customer}', [App\Http\Controllers\CustomerController::class, 'update'])->name('nasabah.update');
        Route::delete('/nasabah/{customer}', [App\Http\Controllers\CustomerController::class, 'destroy'])->name('nasabah.destroy');

        Route::get('/loker', [App\Http\Controllers\LockerController::class, 'index'])->name('loker');

        Route::get('/transaksi/gadai', [App\Http\Controllers\GadaiController::class, 'index'])->name('transaksi.gadai');
        Route::get('/transaksi/gadai/tambah', [App\Http\Controllers\GadaiController::class, 'create'])->name('transaksi.gadai.create');
        Route::post('/transaksi/gadai', [App\Http\Controllers\GadaiController::class, 'store'])->name('transaksi.gadai.store');
        Route::get('/transaksi/gadai/{gadai}', [App\Http\Controllers\GadaiController::class, 'show'])->name('transaksi.gadai.show');
        Route::delete('/transaksi/gadai/{gadai}', [App\Http\Controllers\GadaiController::class, 'destroy'])->name('transaksi.gadai.destroy');
        Route::get('/transaksi/gadai/{gadai}/sbg', [App\Http\Controllers\GadaiController::class, 'downloadSbg'])->name('transaksi.gadai.sbg');

        Route::get('/transaksi/perpanjangan', [App\Http\Controllers\PerpanjanganController::class, 'index'])->name('transaksi.perpanjangan');
        Route::get('/transaksi/perpanjangan/proses', [App\Http\Controllers\PerpanjanganController::class, 'create'])->name('transaksi.perpanjangan.create');
        Route::post('/transaksi/perpanjangan', [App\Http\Controllers\PerpanjanganController::class, 'store'])->name('transaksi.perpanjangan.store');
        Route::get('/transaksi/perpanjangan/{perpanjangan}', [App\Http\Controllers\PerpanjanganController::class, 'show'])->name('transaksi.perpanjangan.show');
        Route::post('/transaksi/perpanjangan/{perpanjangan}/retry', [App\Http\Controllers\PerpanjanganController::class, 'retry'])->name('transaksi.perpanjangan.retry');

        Route::get('/transaksi/pelunasan', [App\Http\Controllers\PelunasanController::class, 'index'])->name('transaksi.pelunasan');
        Route::get('/transaksi/pelunasan/proses', [App\Http\Controllers\PelunasanController::class, 'create'])->name('transaksi.pelunasan.create');
        Route::post('/transaksi/pelunasan', [App\Http\Controllers\PelunasanController::class, 'store'])->name('transaksi.pelunasan.store');
        Route::get('/transaksi/pelunasan/{pelunasan}', [App\Http\Controllers\PelunasanController::class, 'show'])->name('transaksi.pelunasan.show');
        Route::post('/transaksi/pelunasan/{pelunasan}/retry', [App\Http\Controllers\PelunasanController::class, 'retry'])->name('transaksi.pelunasan.retry');

        Route::get('/laporan/harian', [App\Http\Controllers\ReportController::class, 'harian'])->name('laporan.harian');
        Route::get('/laporan/bulanan', [App\Http\Controllers\ReportController::class, 'bulanan'])->name('laporan.bulanan');
        Route::get('/laporan/harian/export', [App\Http\Controllers\ReportController::class, 'exportHarian'])->name('laporan.harian.export');
        Route::get('/laporan/bulanan/export', [App\Http\Controllers\ReportController::class, 'exportBulanan'])->name('laporan.bulanan.export');

        Route::get('/notifikasi', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifikasi');
        Route::post('/notifikasi/read-all', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifikasi.read-all');
        Route::post('/notifikasi/{id}/read', [App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifikasi.read');
    });

// TailAdmin Demo (development only)
// Route::get('/dashboard', fn() => view('pages.dashboard.ecommerce'))->name('dashboard');
Route::get('/calendar', fn() => view('pages.calender'))->name('calendar');
Route::get('/profile', fn() => view('pages.profile'))->name('profile');
Route::get('/form-elements', fn() => view('pages.form.form-elements'))->name('form-elements');
Route::get('/basic-tables', fn() => view('pages.tables.basic-tables'))->name('basic-tables');
Route::get('/blank', fn() => view('pages.blank'))->name('blank');
Route::get('/error-404', fn() => view('pages.errors.error-404'))->name('error-404');
Route::get('/line-chart', fn() => view('pages.chart.line-chart'))->name('line-chart');
Route::get('/bar-chart', fn() => view('pages.chart.bar-chart'))->name('bar-chart');
Route::get('/signin', fn() => view('pages.auth.signin'))->name('signin');
Route::get('/signup', fn() => view('pages.auth.signup'))->name('signup');
Route::get('/alerts', fn() => view('pages.ui-elements.alerts'))->name('alerts');
Route::get('/avatars', fn() => view('pages.ui-elements.avatars'))->name('avatars');
Route::get('/badge', fn() => view('pages.ui-elements.badges'))->name('badges');
Route::get('/buttons', fn() => view('pages.ui-elements.buttons'))->name('buttons');
Route::get('/image', fn() => view('pages.ui-elements.images'))->name('images');
Route::get('/videos', fn() => view('pages.ui-elements.videos'))->name('videos');