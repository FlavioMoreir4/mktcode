<?php

declare(strict_types=1);

namespace App\Domain\Shared\Enums;

enum SocialPlatform: string
{
    // Dev
    case GitHub = 'github';
    case GitLab = 'gitlab';
    case Bitbucket = 'bitbucket';
    case Npm = 'npm';
    case StackOverflow = 'stackoverflow';

    // Profissional
    case LinkedIn = 'linkedin';
    case ProductHunt = 'producthunt';

    // Social
    case X = 'x';
    case Twitter = 'twitter';
    case Instagram = 'instagram';
    case Facebook = 'facebook';
    case Threads = 'threads';
    case Bluesky = 'bluesky';
    case Mastodon = 'mastodon';
    case TikTok = 'tiktok';
    case Reddit = 'reddit';

    // Conteúdo
    case YouTube = 'youtube';
    case Twitch = 'twitch';
    case Spotify = 'spotify';
    case Medium = 'medium';
    case DevTo = 'dev.to';
    case Hashnode = 'hashnode';
    case Substack = 'substack';

    // Design
    case Dribbble = 'dribbble';
    case Behance = 'behance';
    case Figma = 'figma';

    // Comunidade
    case Discord = 'discord';
    case Telegram = 'telegram';
    case WhatsApp = 'whatsapp';

    // Apoio
    case Patreon = 'patreon';
    case KoFi = 'kofi';

    // fallback
    case Website = 'website';

    public function domain(): ?string
    {
        return match ($this) {
            self::GitHub => 'github.com',
            self::GitLab => 'gitlab.com',
            self::Bitbucket => 'bitbucket.org',
            self::Npm => 'npmjs.com',
            self::StackOverflow => 'stackoverflow.com',
            self::LinkedIn => 'linkedin.com',
            self::ProductHunt => 'producthunt.com',
            self::X, self::Twitter => 'x.com',
            self::Instagram => 'instagram.com',
            self::Facebook => 'facebook.com',
            self::Threads => 'threads.net',
            self::Bluesky => 'bsky.app',
            self::Mastodon => 'mastodon.social',
            self::TikTok => 'tiktok.com',
            self::Reddit => 'reddit.com',
            self::YouTube => 'youtube.com',
            self::Twitch => 'twitch.tv',
            self::Spotify => 'spotify.com',
            self::Medium => 'medium.com',
            self::DevTo => 'dev.to',
            self::Hashnode => 'hashnode.com',
            self::Substack => 'substack.com',
            self::Dribbble => 'dribbble.com',
            self::Behance => 'behance.net',
            self::Figma => 'figma.com',
            self::Discord => 'discord.gg',
            self::Telegram => 't.me',
            self::WhatsApp => 'wa.me',
            self::Patreon => 'patreon.com',
            self::KoFi => 'ko-fi.com',
            self::Website => null, // Website é genérico
        };
    }

    // Placeholder
    public function placeholder(): string
    {
        return match ($this) {
            self::GitHub => 'https://github.com/johndoe',
            self::GitLab => 'https://gitlab.com/johndoe',
            self::Bitbucket => 'https://bitbucket.org/johndoe',
            self::Npm => 'https://www.npmjs.com/~johndoe',
            self::StackOverflow => 'https://stackoverflow.com/users/1234567/johndoe',
            self::LinkedIn => 'https://linkedin.com/in/johndoe',
            self::ProductHunt => 'https://www.producthunt.com/@johndoe',
            self::X => 'https://x.com/johndoe',
            self::Twitter => 'https://x.com/johndoe',
            self::Instagram => 'https://instagram.com/johndoe',
            self::Facebook => 'https://facebook.com/johndoe',
            self::Threads => 'https://www.threads.net/@johndoe',
            self::Bluesky => 'https://bsky.app/profile/johndoe.bsky.social',
            self::Mastodon => 'https://mastodon.social/@johndoe',
            self::TikTok => 'https://www.tiktok.com/@johndoe',
            self::Reddit => 'https://reddit.com/user/johndoe',
            self::YouTube => 'https://youtube.com/@johndoe',
            self::Twitch => 'https://twitch.tv/johndoe',
            self::Spotify => 'https://open.spotify.com/user/johndoe',
            self::Medium => 'https://medium.com/@johndoe',
            self::DevTo => 'https://dev.to/johndoe',
            self::Hashnode => 'https://hashnode.com/@johndoe',
            self::Substack => 'https://johndoe.substack.com',
            self::Dribbble => 'https://dribbble.com/johndoe',
            self::Behance => 'https://behance.net/johndoe',
            self::Figma => 'https://figma.com/@johndoe',
            self::Discord => 'https://discord.gg/abc123xyz',
            self::Telegram => 'https://t.me/johndoe',
            self::WhatsApp => 'https://wa.me/5511999999999',
            self::Patreon => 'https://patreon.com/johndoe',
            self::KoFi => 'https://ko-fi.com/johndoe',
            self::Website => 'https://johndoe.com',
            default => '',
        };
    }
}
