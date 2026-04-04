<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\BungaController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Landing page
Route::get('/', fn() => view('frontend.index'))->name('home');


/*
|--------------------------------------------------------------------------
| Dashboard Routes (Per Role)
|--------------------------------------------------------------------------
*/

// Dashboard - Redirect ke dashboard sesuai role
Route::middleware('auth')->get('/dashboard', function () {
    $user = Auth::user();

    return match ($user->role) {
        'superadmin' => view('backend.superadmin.dashboard.index', ['title' => 'Dashboard Superadmin']),
        'admin' => view('backend.admin.dashboard.index', ['title' => 'Dashboard Admin']),
        'officer' => view('backend.officer.dashboard.index', ['title' => 'Dashboard Officer']),
        default => abort(403, 'Role tidak dikenali')
    };
})->name('dashboard');


/*
|--------------------------------------------------------------------------
| Superadmin Only Pages
|--------------------------------------------------------------------------
*/

Route::middleware('role:superadmin')->group(function () {

    // Calendar
    Route::get('/calendar', function () {
        return view('pages.calender', ['title' => 'Calendar']);
    })->name('calendar');

    // Profile
    Route::get('/profile', function () {
        return view('pages.profile', ['title' => 'Profile']);
    })->name('profile');

    // Form Elements
    Route::get('/form-elements', function () {
        return view('pages.form.form-elements', ['title' => 'Form Elements']);
    })->name('form-elements');

    // Tables
    Route::get('/basic-tables', function () {
        return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
    })->name('basic-tables');

    // Blank Page
    Route::get('/blank', function () {
        return view('pages.blank', ['title' => 'Blank']);
    })->name('blank');

    // Charts
    Route::get('/line-chart', function () {
        return view('pages.chart.line-chart', ['title' => 'Line Chart']);
    })->name('line-chart');

    Route::get('/bar-chart', function () {
        return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
    })->name('bar-chart');

    // Master Category (Main Categories) CRUD
    Route::get('/master/category', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/master/category', [CategoryController::class, 'store'])->name('category.store');
    Route::put('/master/category/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/master/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

    // Master Jenis Barang CRUD
    Route::get('/master/jenis-barang', [JenisBarangController::class, 'index'])->name('jenis-barang.index');
    Route::post('/master/jenis-barang', [JenisBarangController::class, 'store'])->name('jenis-barang.store');
    Route::put('/master/jenis-barang/{id}', [JenisBarangController::class, 'update'])->name('jenis-barang.update');
    Route::delete('/master/jenis-barang/{id}', [JenisBarangController::class, 'destroy'])->name('jenis-barang.destroy');

    // Master Bunga CRUD
    Route::get('/master/bunga', [BungaController::class, 'index'])->name('bunga.index');
    Route::post('/master/bunga', [BungaController::class, 'store'])->name('bunga.store');
    Route::get('/master/bunga/{id}/edit', [BungaController::class, 'edit'])->name('bunga.edit');
    Route::put('/master/bunga/{id}', [BungaController::class, 'update'])->name('bunga.update');
    Route::delete('/master/bunga/{id}', [BungaController::class, 'destroy'])->name('bunga.destroy');
});


/*
|--------------------------------------------------------------------------
| Auth Pages (UI)
|--------------------------------------------------------------------------
*/

Route::get('/signin', function () {
    return view('pages.auth.signin', ['title' => 'Sign In']);
})->name('signin');

Route::get('/signup', function () {
    return view('pages.auth.signup', ['title' => 'Sign Up']);
})->name('signup');


/*
|--------------------------------------------------------------------------
| Error Pages
|--------------------------------------------------------------------------
*/

Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');


/*
|--------------------------------------------------------------------------
| UI Elements
|--------------------------------------------------------------------------
*/

Route::middleware('role:superadmin')->group(function () {

    Route::get('/alerts', function () {
        return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
    })->name('alerts');

    Route::get('/avatars', function () {
        return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
    })->name('avatars');

    Route::get('/badge', function () {
        return view('pages.ui-elements.badges', ['title' => 'Badges']);
    })->name('badge');

    Route::get('/buttons', function () {
        return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
    })->name('buttons');

    Route::get('/image', function () {
        return view('pages.ui-elements.images', ['title' => 'Images']);
    })->name('images');

    Route::get('/videos', function () {
        return view('pages.ui-elements.videos', ['title' => 'Videos']);
    })->name('videos');
});
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
