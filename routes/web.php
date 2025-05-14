<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WaliKelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArasController;
use App\Http\Controllers\EligibleController;
use App\Http\Controllers\CekStatusController;
use App\Http\Controllers\ChartController;
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
            Route::get('/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
            Route::put('/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
            Route::delete('/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
        });

        //CRUD Nilai
        Route::prefix('/dashboard/nilai')->group(function (){
            Route::get('/', [NilaiController::class, 'index'])->name('dashboard.nilai');
            Route::get('/{nilai}/edit', [NilaiController::class, 'edit'])->name('nilai.edit');
            Route::put('/{nilai}', [NilaiController::class, 'update'])->name('nilai.update');
            Route::delete('/{nilai}', [NilaiController::class, 'destroy'])->name('nilai.destroy');
        });

        //Perhitungan ARAS
        Route::get('/dashboard/aras/{jurusan}', [ArasController::class, 'hitungDanSimpanEligible'])->name('dashboard.aras');

        //Daftar Eligible
        Route::get('/dashboard/eligible', [ArasController::class, 'showEligible'])->name('dashboard.eligible');
        Route::post('/dashboard/eligible/simpan', [ArasController::class, 'simpanEligible'])->name('eligible.simpan');

        //Import Excel Siswa & Nilai
        Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
        Route::post('/nilai/import', [NilaiController::class, 'import'])->name('nilai.import');

        //Export Siswa & Nilai
        Route::get('/export/eligible/mipa/excel', [EligibleController::class, 'exportMipaExcel'])->name('eligible.mipa.excel');
        Route::get('/export/eligible/mipa/pdf', [EligibleController::class, 'exportMipaPdf'])->name('eligible.mipa.pdf');
        Route::get('/export/eligible/ips/excel', [EligibleController::class, 'exportIpsExcel'])->name('eligible.ips.excel');
        Route::get('/export/eligible/ips/pdf', [EligibleController::class, 'exportIpsPdf'])->name('eligible.ips.pdf');

        //Chart Dashboard
        Route::prefix('charts')->group(function () {
            Route::get('/siswa-per-jurusan', [ChartController::class, 'siswaPerJurusan']);
            Route::get('/eligible-per-jurusan', [ChartController::class, 'eligiblePerJurusan']);
            Route::get('/siswa-per-kelas', [ChartController::class, 'siswaPerKelas']);
            Route::get('/eligible-per-kelas', [ChartController::class, 'eligiblePerKelasGabungan']);
            Route::get('/rata-rata-kriteria', [ChartController::class, 'rataRataKriteria']);
        });
    });
    
    // Untuk Wali Kelas
    Route::middleware(['auth', 'role:wakel'])->group(function () {
        Route::get('/dashboard/wakel', [WaliKelasController::class, 'index'])->name('dashboard.wakel');
    });

    //Guru BK x Wali Kelas
    Route::middleware(['auth', 'role:gurubk,wakel'])->group(function () {
        Route::get('/dashboard/nilai/create', [NilaiController::class, 'create'])->name('nilai.create');
        Route::post('/dashboard/nilai', [NilaiController::class, 'store'])->name('nilai.store');

        Route::get('/create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/', [SiswaController::class, 'store'])->name('siswa.store');
    });

    // Untuk Kepala Sekolah
    Route::middleware('role:kepsek')->get('/dashboard/kepsek', [AuthController::class, 'dashboardKepsek'])->name('dashboard.kepsek');
});