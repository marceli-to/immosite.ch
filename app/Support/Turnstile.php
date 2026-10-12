<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;

/**
 * Cloudflare Turnstile (unsichtbarer Spam-Schutz) fuer alle Formulare.
 *
 * Ohne TURNSTILE_SECRET_KEY wird nicht geprueft, damit Formulare lokal und
 * vor dem Eintreffen der Schluessel testbar bleiben.
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
            // Cloudflare nicht erreichbar: lieber eine Anmeldung zu viel als eine
            // echte verlieren. Der Honeypot faengt die einfachen Bots trotzdem.
            report($e);

            return true;
        }

        return $response->json('success') === true;
    }
}
