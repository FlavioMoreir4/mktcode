<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Post;
use App\Models\Project;
use App\Models\Inquiry;
use App\Models\User;
use App\Observers\InquiryObserver;
use App\Observers\PostObserver;
use App\Observers\ProjectObserver;
use App\Observers\UserObserver;
use App\Services\Telegram\TelegramBotTarget;
use App\Services\Telegram\TelegramNotifier;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TelegramNotifier::class, function () {
            return new TelegramNotifier(TelegramBotTarget::default());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        if (app()->isProduction()) {
            URL::forceRootUrl(config('app.url'));
            URL::forceScheme('https');
        }

        Gate::before(function ($user, $ability) {
            return $user->hasRole('super_admin') ? true : null;
        });

        Post::observe(PostObserver::class);
        Project::observe(ProjectObserver::class);
        User::observe(UserObserver::class);
        Inquiry::observe(InquiryObserver::class);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
