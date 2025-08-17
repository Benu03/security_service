<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    MainController,
    MasterController,
    SecurityController,
    CleanController,
    ReportController

};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['session_key']],function(){

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [MainController::class, 'profile'])->name('profile');
    Route::post('/logout', [MainController::class, 'logout'])->name('logout');
    Route::get('/lobby', [MainController::class, 'lobby'])->name('lobby');
    Route::get('/get-notification', [MainController::class, 'getNotifications'])->name('getnotif');
    Route::post('/update-notif', [MainController::class, 'updateNotifIsread'])->name('updatenotif');


    Route::get('/master-data', [MasterController::class, 'masterData'])->name('master-data');
    Route::get('/users-access', [MasterController::class, 'UsersAccess'])->name('users-access');
    Route::get('/jadwal-presensi', [MasterController::class, 'JadwalPresensi'])->name('jadwal-presensi');
    Route::get('/titik-patroli', [MasterController::class, 'TitikPatroli'])->name('titik-patroli');


    
    Route::get('/security', [SecurityController::class, 'Security'])->name('security');
    Route::get('/presensi', [SecurityController::class, 'Presensi'])->name('presensi');
    Route::get('/patroli', [SecurityController::class, 'Patroli'])->name('patroli');
    Route::get('/temuan', [SecurityController::class, 'Temuan'])->name('temuan');
    


    
    Route::get('/laporan', [ReportController::class, 'Laporan'])->name('laporan');
    
});

Route::fallback(function () {
    return response()->view('global.notification.url_forbidden', ['title' => 'Forbidden'], 403);
});