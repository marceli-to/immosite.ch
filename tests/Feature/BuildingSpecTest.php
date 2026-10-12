<?php

namespace Tests\Feature;

use Tests\TestCase;

class BuildingSpecTest extends TestCase
{
    public function test_sections_render_as_list(): void
    {
        config(['building-spec.display' => 'list', 'building-spec.sections' => ['Küche' => ['Granit', 'Geräte <Siemens>']]]);

        $this->get(route('page.building-spec'))
            ->assertOk()
            ->assertSee('Küche')
            ->assertSee('Granit<br>Geräte &lt;Siemens&gt;', false)
            ->assertDontSee('aria-controls="accordion-', false);
    }

    public function test_sections_render_as_accordion(): void
    {
        config(['building-spec.display' => 'accordion']);

        $this->get(route('page.building-spec'))->assertSee('aria-controls="accordion-', false);
    }
}
