<?php

declare(strict_types=1);

namespace App\Providers;

use App\Actions\Fortify\ResetUserPassword;
use App\Http\Responses\LoginResponse;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        // Fortify::createUsersUsing(CreateNewUser::class);
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(fn (): RedirectResponse => redirect()->route('filament.admin.auth.login'));

        Fortify::resetPasswordView(fn (Request $request): RedirectResponse => redirect()->route(
            'filament.admin.auth.password-reset.reset',
            array_filter([
                'token' => $request->route('token'),
                'email' => $request->string('email')->toString(),
            ]),
        ));

        Fortify::requestPasswordResetLinkView(fn (): RedirectResponse => redirect()->route('filament.admin.auth.password-reset.request'));

        Fortify::verifyEmailView(fn (): RedirectResponse => redirect()->route('filament.admin.auth.email-verification.prompt'));

        Fortify::registerView(fn (): RedirectResponse => redirect()->route('filament.admin.auth.login'));

        Fortify::twoFactorChallengeView(fn (): RedirectResponse => redirect()->route('filament.admin.auth.login'));

        Fortify::confirmPasswordView(fn (): RedirectResponse => redirect()->route('filament.admin.auth.login'));
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}
