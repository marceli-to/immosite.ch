<?php

namespace Tests\Feature;

use App\Livewire\ContactForm;
use Tests\TestCase;

class FullPagesTest extends TestCase
{
    public function test_location_page_shows_distances_and_maps_link(): void
    {
        config([
            'location.google_maps_url' => 'https://maps.example/test',
            'location.distances' => [['destination' => 'Bahnhof Test', 'walk' => 8, 'bike' => null, 'transit' => 4, 'car' => 4]],
        ]);

        $this->get(route('page.location'))
            ->assertOk()
            ->assertSee('Bahnhof Test')
            ->assertSee('8 Min.')
            ->assertSee('aria-label="keine Angabe"', false)
            ->assertSee('href="https://maps.example/test"', false);
    }

    public function test_location_page_hides_maps_button_without_url(): void
    {
        config(['location.google_maps_url' => null]);

        $this->get(route('page.location'))->assertOk()->assertDontSee('Google Maps');
    }

    public function test_contact_page_shows_the_form(): void
    {
        $this->get(route('page.contact'))->assertOk()->assertSeeLivewire(ContactForm::class);
    }

    public function test_navigation_lists_the_full_site_pages(): void
    {
        $this->get(route('page.home'))
            ->assertSee('aria-label="Hauptnavigation"', false)
            ->assertSee('href="'.route('page.location').'"', false)
            ->assertSee('href="'.route('page.contact').'"', false);
    }
}
