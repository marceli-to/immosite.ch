<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class GalleryTest extends TestCase
{
    public function test_home_page_shows_the_hero_slider(): void
    {
        $this->get(route('page.home'))
            ->assertOk()
            ->assertSee('data-gallery=', false)
            ->assertSee('aria-label="Nächstes Bild"', false)
            ->assertSee('fetchpriority="high"', false);
    }

    public function test_single_image_has_no_controls(): void
    {
        $html = Blade::render('<x-gallery.slider :images="[\'img/placeholder-1\']" />');

        $this->assertStringNotContainsString('data-gallery=', $html);
        $this->assertStringNotContainsString('Nächstes Bild', $html);
    }

    public function test_slides_accept_strings_and_arrays(): void
    {
        $html = Blade::render('<x-gallery.slider alt="Standard" :images="[\'img/placeholder-1\', [\'src\' => \'img/placeholder-2\', \'alt\' => \'Eigener Text\']]" />');

        $this->assertStringContainsString('alt="Standard"', $html);
        $this->assertStringContainsString('alt="Eigener Text"', $html);
        $this->assertStringContainsString('aria-label="2 von 2"', $html);
    }

    public function test_grid_links_every_image_to_the_lightbox_group(): void
    {
        $html = Blade::render('<x-gallery.grid group="wohnung" :images="[\'img/placeholder-1\', [\'src\' => \'img/placeholder-2\', \'alt\' => \'Küche\']]" />');

        $this->assertSame(2, substr_count($html, 'data-lightbox="wohnung"'));
        $this->assertStringContainsString('href="'.asset('img/placeholder-1.jpg').'"', $html);
        $this->assertStringContainsString('aria-label="Küche – Bild 2 von 2 vergrössern"', $html);
        $this->assertStringContainsString('placeholder-2.webp', $html);
    }

    public function test_autoplay_can_be_turned_off(): void
    {
        $html = Blade::render('<x-gallery.slider :autoplay="false" :images="[\'img/placeholder-1\', \'img/placeholder-2\']" />');

        $this->assertMatchesRegularExpression('/data-gallery=\'\{(&quot;|")autoplay(&quot;|"):false/', $html);
    }
}
