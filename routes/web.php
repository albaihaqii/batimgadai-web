<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;

// Landing Page
Route::get('/', fn() => view('frontend.index'))->name('home');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])
    ->middleware('auth')
    ->name('notifications.read');

Route::get('/notifications/{notification}/open', [NotificationController::class, 'redirectToReference'])
    ->middleware('auth')
    ->name('notifications.open');

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

// Superadmin
Route::prefix('superadmin')
    ->name('superadmin.')
    ->middleware(['auth', 'role:superadmin'])
    ->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'superadmin'])->name('dashboard');
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

        Route::get('/laporan/harian', [ReportController::class, 'daily'])->name('laporan.harian');
        Route::get('/laporan/harian/export', [ReportController::class, 'exportDaily'])->name('laporan.harian.export');
        Route::get('/laporan/mingguan', [ReportController::class, 'weekly'])->name('laporan.mingguan');
        Route::get('/laporan/mingguan/export', [ReportController::class, 'exportWeekly'])->name('laporan.mingguan.export');
        Route::get('/laporan/bulanan', [ReportController::class, 'monthly'])->name('laporan.bulanan');
        Route::get('/laporan/bulanan/export', [ReportController::class, 'exportMonthly'])->name('laporan.bulanan.export');

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
    });

// Admin
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
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

        Route::get('/laporan/harian', [ReportController::class, 'daily'])->name('laporan.harian');
        Route::get('/laporan/harian/export', [ReportController::class, 'exportDaily'])->name('laporan.harian.export');
        Route::get('/laporan/mingguan', [ReportController::class, 'weekly'])->name('laporan.mingguan');
        Route::get('/laporan/mingguan/export', [ReportController::class, 'exportWeekly'])->name('laporan.mingguan.export');
        Route::get('/laporan/bulanan', [ReportController::class, 'monthly'])->name('laporan.bulanan');
        Route::get('/laporan/bulanan/export', [ReportController::class, 'exportMonthly'])->name('laporan.bulanan.export');

        Route::get('/transaksi/perpanjangan', [App\Http\Controllers\PerpanjanganController::class, 'index'])->name('transaksi.perpanjangan');
        Route::get('/transaksi/perpanjangan/{perpanjangan}', [App\Http\Controllers\PerpanjanganController::class, 'show'])->name('transaksi.perpanjangan.show');
        Route::get('/transaksi/pelunasan', [App\Http\Controllers\PelunasanController::class, 'index'])->name('transaksi.pelunasan');
        Route::get('/transaksi/pelunasan/{pelunasan}', [App\Http\Controllers\PelunasanController::class, 'show'])->name('transaksi.pelunasan.show');
    });

// Officer
Route::prefix('officer')
    ->name('officer.')
    ->middleware(['auth', 'role:officer'])
    ->group(function () {
        Route::get('/dashboard', fn() => view('officer.dashboard'))->name('dashboard');
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
