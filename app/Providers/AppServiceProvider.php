<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Content\Contracts\PostRepository;
use App\Domain\Identity\Contracts\UserRepository;
use App\Domain\Inquiry\Contracts\InquiryRepository;
use App\Domain\Portfolio\Contracts\ProjectRepository;
use App\Events\Inquiries\InquirySubmitted;
use App\Infrastructure\Content\Persistence\Eloquent\EloquentPostRepository;
use App\Infrastructure\Identity\Filament\PanelAccessBridge;
use App\Infrastructure\Identity\Persistence\Eloquent\EloquentUserRepository;
use App\Infrastructure\Inquiry\Listeners\SendInquirySubmittedNotification;
use App\Infrastructure\Inquiry\Persistence\Eloquent\EloquentInquiryRepository;
use App\Infrastructure\Portfolio\Persistence\Eloquent\EloquentProjectRepository;
use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use App\Observers\PostObserver;
use App\Observers\ProjectObserver;
use App\Observers\UserObserver;
use App\Services\Telegram\TelegramBotTarget;
use App\Services\Telegram\TelegramNotifier;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Inertia\ExceptionResponse;
use Inertia\Inertia;
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

        $this->app->bind(InquiryRepository::class, EloquentInquiryRepository::class);
        $this->app->bind(PostRepository::class, EloquentPostRepository::class);
        $this->app->bind(ProjectRepository::class, EloquentProjectRepository::class);
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
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

        PanelAccessBridge::bootstrap($this->app->make(\App\Application\Identity\Services\AdminAccessDecider::class));

        Event::listen(InquirySubmitted::class, SendInquirySubmittedNotification::class);

        Post::observe(PostObserver::class);
        Project::observe(ProjectObserver::class);
        User::observe(UserObserver::class);

        Inertia::handleExceptionsUsing(function (ExceptionResponse $response) {
            if (in_array($response->statusCode(), [403, 404, 500, 503])) {
                return $response->render('Error', [
                    'status' => $response->statusCode(),
                ])->withSharedData();
            }
        });
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
