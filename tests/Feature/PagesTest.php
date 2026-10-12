<?php

namespace Tests\Feature;

use Tests\TestCase;

class PagesTest extends TestCase
{
    public function test_every_configured_page_renders(): void
    {
        foreach (config('site.pages') as $page) {
            $this->get(route($page['route']))
                ->assertOk()
                ->assertSee('<title>', false);
        }
    }

    public function test_page_title_combines_page_and_site_name(): void
    {
        $this->get(route('page.imprint'))
            ->assertSee('<title>Impressum – '.config('site.name').'</title>', false);
    }

    public function test_home_uses_site_title_and_description(): void
    {
        $this->get(route('page.home'))
            ->assertSee('<title>'.e(config('site.title')).'</title>', false)
            ->assertSee('<meta name="description" content="'.e(config('site.description')).'">', false)
            ->assertSee('<meta property="og:image" content="'.asset(config('site.og_image')).'">', false);
    }

    public function test_footer_lists_legal_pages(): void
    {
        $response = $this->get(route('page.home'));

        foreach (collect(config('site.pages'))->where('footer', true) as $page) {
            $response->assertSee('href="'.route($page['route']).'"', false);
        }
    }

    public function test_single_page_site_has_no_navigation(): void
    {
        config(['site.pages' => collect(config('site.pages'))
            ->map(fn ($page) => [...$page, 'nav' => $page['route'] === 'page.home'])
            ->all()]);

        $this->get(route('page.home'))->assertDontSee('aria-label="Hauptnavigation"', false);
    }

    public function test_navigation_shows_nav_pages_and_marks_the_active_one(): void
    {
        config(['site.pages' => collect(config('site.pages'))
            ->map(fn ($page) => [...$page, 'nav' => in_array($page['route'], ['page.home', 'page.imprint'])])
            ->all()]);

        $this->get(route('page.imprint'))
            ->assertSee('aria-label="Hauptnavigation"', false)
            ->assertSee('aria-current="page"', false);
    }

    public function test_privacy_sections_follow_config(): void
    {
        config(['services.google_analytics.id' => null, 'site.fonts.stylesheet' => null]);
        $this->get(route('page.privacy'))
            ->assertDontSee('Google Analytics')
            ->assertDontSee('Adobe Fonts');

        config(['services.google_analytics.id' => 'G-TEST', 'site.fonts.stylesheet' => 'https://use.typekit.net/abc.css']);
        $this->get(route('page.privacy'))
            ->assertSee('Google Analytics')
            ->assertSee('Adobe Fonts');
    }

    public function test_ids_are_unique_on_every_page(): void
    {
        foreach (config('site.pages') as $page) {
            preg_match_all('/\sid="([^"]+)"/', $this->get(route($page['route']))->getContent(), $m);
            $duplicates = array_keys(array_filter(array_count_values($m[1]), fn ($count) => $count > 1));

            $this->assertSame([], $duplicates, "Doppelte IDs auf {$page['route']}");
        }
    }
}
