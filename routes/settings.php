<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('settings', fn () => redirect('/admin/profile'));
    Route::get('settings/profile', fn () => redirect('/admin/profile'))->name('profile.edit');
    Route::get('settings/password', fn () => redirect('/admin/profile'))->name('user-password.edit');
    Route::get('settings/two-factor', fn () => redirect('/admin/profile'))->name('two-factor.show');
});

