<?php

use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\InquiryController;
use App\Http\Controllers\Public\ProjectController;
use App\Http\Controllers\Public\ServiceController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

Route::get('/', HomeController::class)->name('home');

Route::name('public.')->group(function () {
    Route::get('/projetos', ProjectController::class)->name('projects');
    Route::get('/servicos', ServiceController::class)->name('services');
    Route::get('/sobre', AboutController::class)->name('about');
    Route::get('/contato', ContactController::class)->name('contact');

    Route::post('/inquiry', [InquiryController::class, 'store'])->name('inquiry.store');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::get('/debug-https', function () {
        return response()->json([
            'isSecure' => request()->isSecure(),
            'scheme' => request()->getScheme(),
            'HTTPS' => $_SERVER['HTTPS'] ?? 'não definido',
            'X-Forwarded-Proto' => request()->header('X-Forwarded-Proto'),
            'url_dashboard' => url('/dashboard'),
            'APP_URL' => config('app.url'),
            'forceRootUrl_active' => URL::to('/'),
        ]);
    });
});

require __DIR__.'/settings.php';
