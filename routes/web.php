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
use App\Http\Controllers\KepalaSekolahController;
use App\Http\Controllers\ChangeRequestController;
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

Route::get('/export/eligible/mipa/excel', [EligibleController::class, 'exportMipaExcel'])->name('eligible.mipa.excel');
Route::get('/export/eligible/mipa/pdf', [EligibleController::class, 'exportMipaPdf'])->name('eligible.mipa.pdf');
Route::get('/export/eligible/ips/excel', [EligibleController::class, 'exportIpsExcel'])->name('eligible.ips.excel');
Route::get('/export/eligible/ips/pdf', [EligibleController::class, 'exportIpsPdf'])->name('eligible.ips.pdf');

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


        //Export Siswa & Nilai

        //Approve Change Request
        Route::get('/change-requests', [ChangeRequestController::class, 'index'])->name('changeRequests.index');
        Route::post('/change-requests/{changeRequest}/approve', [ChangeRequestController::class, 'approve'])->name('changeRequests.approve');
        Route::post('/change-requests/{changeRequest}/reject', [ChangeRequestController::class, 'reject'])->name('changeRequests.reject');
    });
    
    // Untuk Wali Kelas
    Route::middleware(['auth', 'role:wakel'])->group(function () {
        Route::get('/dashboard/wakel', [WaliKelasController::class, 'index'])->name('dashboard.wakel');

        //Change Request    
        Route::post('/nilai/{nilai}/request-edit', [NilaiController::class, 'requestEdit'])->name('nilai.requestEdit');
        Route::post('/nilai/{nilai}/request-delete', [NilaiController::class, 'requestDelete'])->name('nilai.requestDelete');

        //Change Request    
        Route::post('/siswa/{siswa}/request-edit', [SiswaController::class, 'requestEdit'])->name('siswa.requestEdit');
        Route::post('/siswa/{siswa}/request-delete', [SiswaController::class, 'requestDelete'])->name('siswa.requestDelete');
        
        Route::get('/request-edit/{model}/{id}', [ChangeRequestController::class, 'editRequest'])->name('changeRequests.editRequest');
        Route::post('/request-edit/{model}/{id}', [ChangeRequestController::class, 'storeRequest'])->name('changeRequests.storeRequest');
    });

    //Guru BK x Wali Kelas
    Route::middleware(['auth', 'role:gurubk,wakel'])->group(function () {
        Route::get('/dashboard/nilai/create', [NilaiController::class, 'create'])->name('nilai.create');
        Route::post('/dashboard/nilai', [NilaiController::class, 'store'])->name('nilai.store');

        Route::get('/create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/', [SiswaController::class, 'store'])->name('siswa.store');

        Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
        Route::post('/nilai/import', [NilaiController::class, 'import'])->name('nilai.import');
    });
    
    //Guru BK x Kepala Sekolah
    Route::middleware(['auth', 'role:gurubk,kepsek'])->group(function () {
        //Chart Dashboard
        Route::prefix('charts')->group(function () {
            Route::get('/siswa-per-jurusan', [ChartController::class, 'siswaPerJurusan']);
            Route::get('/eligible-per-jurusan', [ChartController::class, 'eligiblePerJurusan']);
            Route::get('/siswa-per-kelas', [ChartController::class, 'siswaPerKelas']);
            Route::get('/eligible-per-kelas', [ChartController::class, 'eligiblePerKelasGabungan']);
            Route::get('/rata-rata-kriteria', [ChartController::class, 'rataRataKriteria']);
        });
    });

    // Untuk Kepala Sekolah
    Route::middleware(['auth', 'role:kepsek'])->group(function () {
        Route::get('/dashboard/kepsek', [KepalaSekolahController::class, 'index'])->name('dashboard.kepsek');

        //Daftar Eligible
        Route::get('/dashboard/eligible', [ArasController::class, 'showEligible'])->name('dashboard.eligible');
        Route::post('/dashboard/eligible/simpan', [ArasController::class, 'simpanEligible'])->name('eligible.simpan');
    });
});