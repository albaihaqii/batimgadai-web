<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Landing Page
Route::get('/', fn() => view('frontend.index'))->name('home');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Scan QR Loker
Route::get('/loker/{kode_loker}', [App\Http\Controllers\LockerController::class, 'scan'])->name('loker.scan');

// Superadmin
Route::prefix('superadmin')
    ->name('superadmin.')
    ->middleware(['auth', 'role:superadmin'])
    ->group(function () {
        Route::get('/dashboard', fn() => view('superadmin.dashboard'))->name('dashboard');
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

        // Transactions
        Route::get('/transaksi', [App\Http\Controllers\SuperadminTransactionController::class, 'index'])->name('transactions');
        Route::get('/transaksi/gadai', [App\Http\Controllers\SuperadminTransactionController::class, 'pawn'])->name('transactions.pawn');
        Route::get('/transaksi/gadai/tambah', [App\Http\Controllers\SuperadminTransactionController::class, 'createPawn'])->name('transactions.pawn.create');
        Route::post('/transaksi/gadai', [App\Http\Controllers\SuperadminTransactionController::class, 'storePawn'])->name('transactions.pawn.store');
        Route::get('/transaksi/gadai/{id}', [App\Http\Controllers\SuperadminTransactionController::class, 'showPawn'])->name('transactions.show_pawn');
        Route::get('/transaksi/gadai/{id}/edit', [App\Http\Controllers\SuperadminTransactionController::class, 'editPawn'])->name('transactions.pawn.edit');
        Route::put('/transaksi/gadai/{id}', [App\Http\Controllers\SuperadminTransactionController::class, 'updatePawn'])->name('transactions.pawn.update');
        Route::delete('/transaksi/gadai/{id}', [App\Http\Controllers\SuperadminTransactionController::class, 'destroyPawn'])->name('transactions.pawn.destroy');
        Route::put('/transaksi/gadai/{id}/status', [App\Http\Controllers\SuperadminTransactionController::class, 'updatePawnStatus'])->name('transactions.update_pawn_status');
        Route::get('/transaksi/perpanjangan', [App\Http\Controllers\SuperadminTransactionController::class, 'extension'])->name('transactions.extension');
        Route::get('/transaksi/pelunasan', [App\Http\Controllers\SuperadminTransactionController::class, 'redemption'])->name('transactions.redemption');
    });
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
