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
    Route::get('/karyawan', [MasterController::class, 'karyawan'])->name('karyawan');
    Route::get('/perusahaan', [MasterController::class, 'perusahaan'])->name('perusahaan');
    Route::get('/presensi', [MasterController::class, 'presensi'])->name('presensi');


    
    Route::get('/security', [SecurityController::class, 'Security'])->name('security');
    
    
    Route::get('/cleaning', [CleanController::class, 'Cleaning'])->name('cleaning');
    
    
    Route::get('/report', [ReportController::class, 'report'])->name('report');
    
});

Route::fallback(function () {
    return response()->view('global.notification.url_forbidden', ['title' => 'Forbidden'], 403);
});