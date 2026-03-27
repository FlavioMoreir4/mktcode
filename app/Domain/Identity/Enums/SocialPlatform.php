<?php

declare(strict_types=1);

namespace App\Domain\Identity\Enums;

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
