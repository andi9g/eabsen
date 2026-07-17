<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Security;
use App\Livewire\Settings\LoginSocialite;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth',"sessionku"])->group(function () {
    Route::redirect('settings', 'settings/profile')->name('settings');

    Route::livewire('settings/profile', Profile::class)->name('profile.edit');
    Route::livewire('settings/akungoogle', LoginSocialite::class)->name('kaitkan.akun');
});

Route::middleware(['auth', 'verified', "sessionku"])->group(function () {
    Route::livewire('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::livewire('settings/security', Security::class)
    // ->middleware([
    //     'password.confirm',
    // ])
    ->name('security.edit');
});
