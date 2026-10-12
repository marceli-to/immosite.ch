<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Sends a form submission to Melon as an interested-party lead (module:melon-lead-hook).
 *
 * Deliberately best effort: failures are logged but never reach the visitor. A Melon
 * outage must not block the form; the enquiry has been delivered by mail anyway.
 */
class SendLeadToMelon
{
    /** @param  array<string, string|null>  $data  ContactFields::contactData() */
    public function send(array $data): bool
    {
        if (! config('melon-lead.enabled')) {
            return false;
        }

        if (blank(config('melon-lead.url'))) {
            Log::warning('Melon lead hook is enabled but MELON_HOOK_URL is missing.');

            return false;
        }

        try {
            $response = Http::withBasicAuth((string) config('melon-lead.username'), (string) config('melon-lead.password'))
                ->timeout(config('melon-lead.timeout'))
                ->acceptJson()
                ->asJson()
                ->post(config('melon-lead.url'), $this->payload($data));
        } catch (\Throwable $e) {
            Log::warning('Melon lead hook unreachable.', ['message' => $e->getMessage()]);

            return false;
        }

        if ($response->failed()) {
            Log::warning('Melon lead hook rejected the lead.', ['status' => $response->status(), 'body' => Str::limit($response->body(), 500)]);

            return false;
        }

        return true;
    }

    /** Field names from Melon's "API – Schnittstelle Interessentenformular". */
    public function payload(array $data): array
    {
        [$postcode, $city] = $this->splitZipCity($data['zip_city'] ?? '');

        return array_filter([
            'firstname' => $data['first_name'] ?? null,
            'name' => $data['last_name'] ?? null,
            'street_nr' => $data['street'] ?? null,
            'postcode' => $postcode,
            'city' => $city,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'remarks' => $data['message'] ?? null,
            'howfound' => 'project_website',
        ], fn ($value) => filled($value));
    }

    /** The form has one "PLZ/Ort" field, Melon wants both; without a leading postcode everything goes to city. */
    private function splitZipCity(string $value): array
    {
        if (preg_match('/^\s*(\d{4,6})\s+(.+)$/u', $value, $m)) {
            return [(int) $m[1], trim($m[2])];
        }

        return [null, trim($value) ?: null];
    }
}
