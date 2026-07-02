<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SholatController;
use App\Http\Controllers\TilawahController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])->name('register.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::view('/menunggu', 'auth.pending')->name('menunggu');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('beranda');

    Route::get('/jadwal', [MeetingController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal', [MeetingController::class, 'store'])->name('jadwal.store');
    Route::put('/jadwal/{meeting}', [MeetingController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{meeting}', [MeetingController::class, 'destroy'])->name('jadwal.destroy');

    Route::get('/absensi', [AttendanceController::class, 'index'])->name('absensi.index');
    Route::post('/absensi', [AttendanceController::class, 'set'])->name('absensi.set');

    Route::get('/anggota', [MemberController::class, 'index'])->name('anggota.index');
    Route::post('/anggota', [MemberController::class, 'store'])->name('anggota.store');
    Route::post('/anggota/{member}/setujui', [MemberController::class, 'approve'])->name('anggota.approve');
    Route::delete('/anggota/{member}/tolak', [MemberController::class, 'reject'])->name('anggota.reject');
    Route::put('/anggota/{member}', [MemberController::class, 'update'])->name('anggota.update');
    Route::delete('/anggota/{member}', [MemberController::class, 'destroy'])->name('anggota.destroy');

    Route::get('/keuangan', [FinanceController::class, 'index'])->name('keuangan.index');
    Route::post('/keuangan', [FinanceController::class, 'store'])->name('keuangan.store');
    Route::delete('/keuangan/{finance}', [FinanceController::class, 'destroy'])->name('keuangan.destroy');

    Route::get('/pengumuman', [AnnouncementController::class, 'index'])->name('pengumuman.index');
    Route::post('/pengumuman', [AnnouncementController::class, 'store'])->name('pengumuman.store');
    Route::post('/pengumuman/{announcement}/pin', [AnnouncementController::class, 'togglePin'])->name('pengumuman.pin');
    Route::delete('/pengumuman/{announcement}', [AnnouncementController::class, 'destroy'])->name('pengumuman.destroy');

    Route::get('/materi', [NoteController::class, 'index'])->name('materi.index');
    Route::post('/materi', [NoteController::class, 'store'])->name('materi.store');
    Route::delete('/materi/{materi}', [NoteController::class, 'destroy'])->name('materi.destroy');

    Route::get('/tilawah', [TilawahController::class, 'index'])->name('tilawah.index');
    Route::post('/tilawah', [TilawahController::class, 'store'])->name('tilawah.store');
    Route::delete('/tilawah/{tilawah}', [TilawahController::class, 'destroy'])->name('tilawah.destroy');

    Route::get('/sholat', [SholatController::class, 'index'])->name('sholat.index');
    Route::post('/sholat/kota', [SholatController::class, 'setCity'])->name('sholat.city');

    Route::view('/doa', 'doa', ['doa' => config('doa')])->name('doa');
    Route::view('/menu', 'menu')->name('menu');

    Route::get('/pengaturan', [SettingController::class, 'edit'])->name('pengaturan.edit');
    Route::put('/pengaturan', [SettingController::class, 'update'])->name('pengaturan.update');
    Route::put('/pengaturan/sandi', [SettingController::class, 'updatePassword'])->name('pengaturan.sandi');
});
