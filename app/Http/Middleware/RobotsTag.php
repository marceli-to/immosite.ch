<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Setzt auf nicht-oeffentlichen Umgebungen (ROBOTS_NOINDEX=true) den Header
 * X-Robots-Tag. Anders als robots.txt verhindert der nicht nur das Crawlen,
 * sondern auch das Indexieren bereits bekannter URLs.
 */
class RobotsTag
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (config('seo.noindex')) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $response;
    }
}
