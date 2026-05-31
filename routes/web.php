<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Socialite;
use App\Http\Controllers\SocialiteC;
use App\Http\Controllers\superadminC;

Route::view('/', 'welcome')->name('home');

Route::get('/auth/redirect', [SocialiteC::class, 'socialite'])->name('auth.socialite');
 
Route::get('/auth/google/callback', [SocialiteC::class, 'callback'])->name('auth.callback');

Route::middleware(['auth', 'verified', 'BuatAkses', 'PasswordDefault'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    
    Route::middleware(['admin'])->group(function () {
        Route::get('admin', [superadminC::class, "admin"])->name('admin');
        Route::get('pegawai', [superadminC::class, "pegawai"])->name('pegawai');
        Route::get('user', [superadminC::class, "user"])->name('user');
        
    });



});

require __DIR__.'/settings.php';
