<?php

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\InquiryController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::post('/inquiry', InquiryController::class)->name('inquiry.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
