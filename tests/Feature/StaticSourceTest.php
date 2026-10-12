<?php

namespace Tests\Feature;

use App\Apartments\Sources\StaticSource;
use App\Apartments\Status;
use Tests\TestCase;

class StaticSourceTest extends TestCase
{
    public function test_it_reads_config_in_order(): void
    {
        config(['apartments-static' => [
            ['ref' => 'B.1', 'status' => 'rented', 'rooms' => 3.5],
            ['ref' => 'A.1', 'status' => 'free'],
        ]]);

        $apartments = (new StaticSource)->all();

        $this->assertSame(['B.1', 'A.1'], array_map(fn ($a) => $a->ref, $apartments));
        $this->assertSame(Status::Rented, $apartments[0]->status);
    }

    public function test_starter_data_renders_on_the_page(): void
    {
        config(['apartments.driver' => 'static']);

        $this->get(route('page.apartments'))->assertOk()->assertSee('data-apartment="A.01"', false);
    }
}
