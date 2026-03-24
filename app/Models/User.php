<?php

declare(strict_types=1);

namespace App\Models;

use App\Filament\Resources\Concerns\HasRichEditorRendering;
use App\Infrastructure\Identity\Filament\PanelAccessBridge;
use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthentication;
use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthenticationRecovery;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthenticationRecovery;
use Filament\Auth\MultiFactor\Email\Concerns\InteractsWithEmailAuthentication;
use Filament\Auth\MultiFactor\Email\Contracts\HasEmailAuthentication;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAppAuthentication, HasAppAuthenticationRecovery, HasEmailAuthentication, HasMedia, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    use HasRichEditorRendering;
    use HasRoles;
    use InteractsWithAppAuthentication;
    use InteractsWithAppAuthenticationRecovery;
    use InteractsWithEmailAuthentication;
    use InteractsWithMedia;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'title',
        'bio',
        'location',
        'social_links',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected $appends = ['profile_photo_url', 'cover_photo_url'];

    /**
     * Filament is the source of truth for admin/session flows in this application.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() !== 'admin'
            || PanelAccessBridge::canAccessAdminPanel($this);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'social_links' => 'array',
        ];
    }

    protected function getEditorContent(): string|array|null
    {
        return $this->bio;
    }

    protected function getRichContentField(): string
    {
        return 'bio';
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('profile_photo');
    }

    public function getCoverPhotoUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('cover_photo');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_photo')
            ->useDisk('public')
            ->singleFile();

        $this->addMediaCollection('cover_photo')
            ->useDisk('public')
            ->singleFile();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'user_id');
    }
}
