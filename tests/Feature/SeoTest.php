<?php

namespace Tests\Feature;

use Tests\TestCase;

class SeoTest extends TestCase
{
    public function test_robots_txt_blocks_everything_when_noindex(): void
    {
        config(['seo.noindex' => true]);

        $this->get('/robots.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('Disallow: /')
            ->assertDontSee('Sitemap:');
    }

    public function test_robots_txt_allows_crawling_and_points_to_sitemap_on_live(): void
    {
        config(['seo.noindex' => false]);

        $this->get('/robots.txt')
            ->assertOk()
            ->assertDontSee('Disallow: /')
            ->assertSee('Sitemap: '.route('sitemap'));
    }

    public function test_x_robots_tag_header_follows_noindex(): void
    {
        config(['seo.noindex' => true]);
        $this->get(route('page.home'))->assertHeader('X-Robots-Tag', 'noindex, nofollow');

        config(['seo.noindex' => false]);
        $this->get(route('page.home'))->assertHeaderMissing('X-Robots-Tag');
    }

    public function test_sitemap_lists_configured_pages(): void
    {
        $response = $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml');

        $this->assertStringStartsWith('<?xml version="1.0" encoding="UTF-8"?>', $response->getContent());

        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml, 'Sitemap ist kein gueltiges XML');

        $expected = collect(config('site.pages'))->filter(fn ($page) => $page['sitemap']);
        $this->assertCount($expected->count(), $xml->url);

        foreach ($expected as $page) {
            $response->assertSee('<loc>'.route($page['route']).'</loc>', false);
        }
    }
}
