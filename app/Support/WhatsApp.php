<?php

declare(strict_types=1);

namespace App\Support;

class WhatsApp
{
    /**
     * Sanitizes and formats a phone number for WhatsApp.
     * Assumes Brazil country code (55) if not provided.
     */
    public static function formatNumber(string $phone): ?string
    {
        $numbersOnly = preg_replace('/\D/', '', $phone);

        if (empty($numbersOnly)) {
            return null;
        }

        if (mb_strlen($numbersOnly) >= 10 && mb_strlen($numbersOnly) <= 11) {
            $numbersOnly = '55'.$numbersOnly;
        }

        // WhatsApp numbers (in Brazil) typically have 12 to 13 digits (55 + DDD (2) + 8 or 9 digits)
        if (mb_strlen($numbersOnly) < 12 || mb_strlen($numbersOnly) > 13) {
            return null;
        }

        return $numbersOnly;
    }

    /**
     * Generates a wa.me link with an optional message.
     */
    public static function makeLink(?string $phone, ?string $message = null): ?string
    {
        if (blank($phone)) {
            return null;
        }

        $formattedPhone = self::formatNumber($phone);

        if (! $formattedPhone) {
            return null;
        }

        $url = 'https://wa.me/'.$formattedPhone;

        if (filled($message)) {
            $url .= '?text='.urlencode($message);
        }

        return $url;
    }
}
