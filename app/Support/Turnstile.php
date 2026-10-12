<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;

/**
 * Cloudflare Turnstile (invisible spam protection) for all forms.
 *
 * Without TURNSTILE_SECRET_KEY nothing is checked, so forms stay testable
 * locally and before the keys arrive.
 */
class Turnstile
{
    public static function enabled(): bool
    {
        return filled(config('services.turnstile.secret_key'));
    }

    public static function verify(?string $token): bool
    {
        if (! self::enabled()) {
            return true;
        }

        if (blank($token)) {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => config('services.turnstile.secret_key'),
                    'response' => $token,
                    'remoteip' => request()->ip(),
                ]);
        } catch (\Throwable $e) {
            // Cloudflare unreachable: better one registration too many than losing a
            // real one. The honeypot still catches the simple bots.
            report($e);

            return true;
        }

        return $response->json('success') === true;
    }
}
