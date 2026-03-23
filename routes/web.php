<?php

declare(strict_types=1);

use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\InquiryController;
use App\Http\Controllers\Public\PostController;
use App\Http\Controllers\Public\ProjectController;
use App\Http\Controllers\Public\ServiceController;
use App\Http\Controllers\Public\UserController as PublicUserController;
use App\SEO\Services\RobotsGenerator;
use App\SEO\Services\SitemapGenerator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

Route::get('/sitemap.xml', function () {
    app(SitemapGenerator::class)->generate();

    return response()->file(public_path('sitemap.xml'), [
        'Content-Type' => 'application/xml',
    ]);
})->name('sitemap');

Route::get('/robots.txt', function () {
    return response(
        app(RobotsGenerator::class)->generate(),
        200,
        ['Content-Type' => 'text/plain']
    );
});

// Sub-sitemaps também precisam de rota (ou o Laravel serve via public/ direto)
// Se o servidor servir public/ diretamente, as rotas abaixo são opcionais:
Route::get('/sitemap-{type}.xml', function (string $type) {
    $allowed = ['pages', 'posts', 'projects', 'users'];
    abort_unless(in_array($type, $allowed), 404);

    return response()->file(
        public_path("sitemap-{$type}.xml"),
        ['Content-Type' => 'application/xml']
    );
});

Route::get('/', HomeController::class)->name('home');

Route::name('public.')->group(function () {

    // Projects
    Route::get('/projetos', [ProjectController::class, 'index'])->name('projects');
    Route::get('/projetos/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');

    Route::get('/servicos', ServiceController::class)->name('services');
    Route::get('/sobre', AboutController::class)->name('about');
    Route::get('/contato', ContactController::class)->name('contact');

    // Blog
    Route::get('/blog', [PostController::class, 'index'])->name('blog.index');
    Route::get('/blog/{post:slug}', [PostController::class, 'show'])->name('blog.show');

    // User Profiles
    Route::get('/u/{user:username}', [PublicUserController::class, 'show'])->name('user.show');

    Route::post('/inquiry', [InquiryController::class, 'store'])->name('inquiry.store');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

if (app()->isLocal()) {
    Route::middleware(['auth', 'verified'])->get('/debug-https', function () {
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
}

require __DIR__.'/settings.php';
