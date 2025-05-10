<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArasController;
use App\Http\Controllers\EligibleController;
use App\Http\Controllers\CekStatusController;
use App\Models\Eligible;
use App\Models\Nilai;

Route::get('/', function () {
    return view('home');
});

Route::get('/cekStatus', function () {
    return view('cekStatus');
});

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Cek Status Eligible
Route::get('/cek-status', [CekStatusController::class, 'form'])->name('cekstatus.form');
Route::post('/cek-status', [CekStatusController::class, 'cek'])->name('cekstatus.cek');

// Dashboard Routes
Route::middleware(['auth'])->group(function () {
    // Untuk Guru BK
    Route::middleware('role:gurubk')->group(function () {
        Route::get('/dashboard/gurubk', [DashboardController::class, 'index'])->name('dashboard.index');

        //CRUD User
        Route::prefix('/dashboard/user')->group(function (){
            Route::get('/', [UserController::class, 'index'])->name('dashboard.user');
            Route::get('/create', [UserController::class, 'create'])->name('user.create');
            Route::post('/', [UserController::class, 'store'])->name('user.store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('user.update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('user.destroy');
        });
        
        // CRUD Siswa
        Route::prefix('/dashboard/siswa')->group(function () {
            Route::get('/', [SiswaController::class, 'index'])->name('dashboard.siswa');
            Route::get('/create', [SiswaController::class, 'create'])->name('siswa.create');
            Route::post('/', [SiswaController::class, 'store'])->name('siswa.store');
            Route::get('/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
            Route::put('/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
            Route::delete('/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
        });

        //CRUD Nilai
        Route::prefix('/dashboard/nilai')->group(function (){
            Route::get('/', [NilaiController::class, 'index'])->name('dashboard.nilai');
            Route::get('/create', [NilaiController::class, 'create'])->name('nilai.create');
            Route::post('/', [NilaiController::class, 'store'])->name('nilai.store');
            Route::get('/{nilai}/edit', [NilaiController::class, 'edit'])->name('nilai.edit');
            Route::put('/{nilai}', [NilaiController::class, 'update'])->name('nilai.update');
            Route::delete('/{nilai}', [NilaiController::class, 'destroy'])->name('nilai.destroy');
        });

        Route::get('/dashboard/eligible', [ArasController::class, 'showEligible'])->name('dashboard.eligible');
        Route::post('/dashboard/eligible/simpan', [ArasController::class, 'simpanEligible'])->name('eligible.simpan');

        Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
        Route::post('/nilai/import', [NilaiController::class, 'import'])->name('nilai.import');

        Route::get('/export/eligible/mipa/excel', [EligibleController::class, 'exportMipaExcel'])->name('eligible.mipa.excel');
        Route::get('/export/eligible/mipa/pdf', [EligibleController::class, 'exportMipaPdf'])->name('eligible.mipa.pdf');
        Route::get('/export/eligible/ips/excel', [EligibleController::class, 'exportIpsExcel'])->name('eligible.ips.excel');
        Route::get('/export/eligible/ips/pdf', [EligibleController::class, 'exportIpsPdf'])->name('eligible.ips.pdf');

        //Route::get('/dashboard/peringkat', [ArasController::class, 'getPerhitunganLengkap'])->name('dashboard.peringkat');
    });

    // Untuk Wali Kelas
    Route::middleware('role:wakel')->get('/dashboard/wakel', [AuthController::class, 'dashboardWakel'])->name('dashboard.wakel');

    // Untuk Kepala Sekolah
    Route::middleware('role:kepsek')->get('/dashboard/kepsek', [AuthController::class, 'dashboardKepsek'])->name('dashboard.kepsek');
});