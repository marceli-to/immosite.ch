<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sets the X-Robots-Tag header on non-public environments (ROBOTS_NOINDEX=true).
 * Unlike robots.txt, it prevents not only crawling but also indexing of
 * URLs that are already known.
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
