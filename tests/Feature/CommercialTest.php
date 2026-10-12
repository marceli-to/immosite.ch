<?php

namespace Tests\Feature;

use Tests\Support\FakeApartmentSource;
use Tests\TestCase;

class CommercialTest extends TestCase
{
    public function test_static_source_renders_with_commercial_columns(): void
    {
        config([
            'commercial.source' => 'static',
            'commercial.objects' => [['ref' => 'G.01', 'floor' => 'EG', 'area' => 203, 'rent_net' => 5400, 'status' => 'free']],
            'commercial.columns' => ['ref' => ['Objekt', 'Objekt'], 'rent_net' => ['Nettomiete', 'Netto']],
        ]);

        $this->get(route('page.commercial'))
            ->assertOk()
            ->assertSee('data-apartment="G.01"', false)
            ->assertSee('5&#039;400', false)
            ->assertSee('Objekt')
            ->assertDontSee('Zimmer</th>', false);
    }

    public function test_apartments_source_lists_only_commercial_units(): void
    {
        config([
            'commercial.source' => 'apartments',
            'apartments.driver' => 'fake',
            'apartments.drivers.fake' => FakeApartmentSource::class,
            'testing.apartments' => [
                ['ref' => 'W.1', 'status' => 'free', 'type' => 'apartment'],
                ['ref' => 'G.1', 'status' => 'free', 'type' => 'commercial'],
            ],
        ]);

        $this->get(route('page.commercial'))
            ->assertSee('data-apartment="G.1"', false)
            ->assertDontSee('data-apartment="W.1"', false);
    }

    public function test_empty_list_shows_a_message(): void
    {
        config(['commercial.source' => 'static', 'commercial.objects' => []]);

        $this->get(route('page.commercial'))->assertSee('keine Gewerbeflächen ausgeschrieben');
    }
}
