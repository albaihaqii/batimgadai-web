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
