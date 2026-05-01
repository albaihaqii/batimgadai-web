<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\OfficerController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\LockerController;
use App\Http\Controllers\Api\GadaiController;
use App\Http\Controllers\Api\ApprovalController;

// ─── PUBLIC ───────────────────────────────────────
Route::post('/login', [AuthController::class, 'login']);

// ─── PROTECTED ────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // ── SUPERADMIN ──────────────────────────────
    Route::middleware('role:superadmin')->prefix('superadmin')->group(function () {
        Route::apiResource('nasabah', CustomerController::class)->parameters(['nasabah' => 'customer']);
        Route::apiResource('pimpinan', AdminController::class)->parameters(['pimpinan' => 'admin']);
        Route::apiResource('petugas', OfficerController::class)->parameters(['petugas' => 'officer']);
        Route::apiResource('cabang', BranchController::class)->parameters(['cabang' => 'branch']);
        Route::get('loker', [LockerController::class, 'index']);
        Route::post('loker', [LockerController::class, 'store']);
        Route::get('loker/{locker}', [LockerController::class, 'show']);
        Route::delete('loker/{locker}', [LockerController::class, 'destroy']);
        Route::apiResource('transaksi/gadai', GadaiController::class)->parameters(['gadai' => 'gadai'])->except(['update']);
        Route::get('approval/gadai', [ApprovalController::class, 'index']);
        Route::get('approval/gadai/{gadai}', [ApprovalController::class, 'show']);
        Route::post('approval/gadai/{gadai}', [ApprovalController::class, 'proses']);
    });

    // ── ADMIN ───────────────────────────────────
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::apiResource('nasabah', CustomerController::class)->parameters(['nasabah' => 'customer']);
        Route::apiResource('petugas', OfficerController::class)->parameters(['petugas' => 'officer']);
        Route::get('loker', [LockerController::class, 'index']);
        Route::post('loker', [LockerController::class, 'store']);
        Route::get('loker/{locker}', [LockerController::class, 'show']);
        Route::delete('loker/{locker}', [LockerController::class, 'destroy']);
        Route::get('transaksi/gadai', [GadaiController::class, 'index']);
        Route::get('transaksi/gadai/{gadai}', [GadaiController::class, 'show']);
        Route::get('approval/gadai', [ApprovalController::class, 'index']);
        Route::get('approval/gadai/{gadai}', [ApprovalController::class, 'show']);
        Route::post('approval/gadai/{gadai}', [ApprovalController::class, 'proses']);
    });

    // ── OFFICER ─────────────────────────────────
    Route::middleware('role:officer')->prefix('officer')->group(function () {
        Route::apiResource('nasabah', CustomerController::class)->parameters(['nasabah' => 'customer']);
        Route::get('loker', [LockerController::class, 'index']);
        Route::get('loker/{locker}', [LockerController::class, 'show']);
        Route::get('transaksi/gadai', [GadaiController::class, 'index']);
        Route::post('transaksi/gadai', [GadaiController::class, 'store']);
        Route::get('transaksi/gadai/{gadai}', [GadaiController::class, 'show']);
        Route::delete('transaksi/gadai/{gadai}', [GadaiController::class, 'destroy']);
    });
});