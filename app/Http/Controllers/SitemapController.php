<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

/**
 * /sitemap.xml from config('site.pages'), so navigation and sitemap don't
 * drift apart. Uses XMLWriter instead of a Blade view: the editor flags XML in
 * a .blade.php as broken HTML.
 */
class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $xml = new \XMLWriter;
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElementNs(null, 'urlset', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach (config('site.pages') as $page) {
            if (! $page['sitemap']) {
                continue;
            }

            [$changefreq, $priority] = $page['sitemap'];

            $xml->startElement('url');
            $xml->writeElement('loc', route($page['route']));
            $xml->writeElement('changefreq', $changefreq);
            $xml->writeElement('priority', $priority);
            $xml->endElement();
        }

        $xml->endElement();
        $xml->endDocument();

        return response($xml->outputMemory(), 200, ['Content-Type' => 'application/xml']);
    }
}
