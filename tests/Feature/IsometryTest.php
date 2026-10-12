<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\Support\FakeApartmentSource;
use Tests\TestCase;

class IsometryTest extends TestCase
{
    private string $output;

    protected function setUp(): void
    {
        parent::setUp();

        $this->output = sys_get_temp_dir().'/building-'.uniqid().'.svg';
        config(['isometry.output' => $this->output]);
    }

    protected function tearDown(): void
    {
        File::delete($this->output);

        parent::tearDown();
    }

    private function source(string $body): string
    {
        $path = sys_get_temp_dir().'/source-'.uniqid().'.svg';
        File::put($path, <<<SVG
        <?xml version="1.0" encoding="UTF-8"?>
        <svg id="Ebene_1" data-name="Ebene 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300" width="400" height="300">
          <!-- Generator: Adobe Illustrator -->
          <defs><style>.st0 { fill: #d0dde0; } .st1 { fill: #e0e9eb; } .st2 { fill: #ccc; }</style></defs>
          {$body}
        </svg>
        SVG);

        return $path;
    }

    public function test_build_marks_floors_apartments_and_faces(): void
    {
        config(['isometry.floors' => ['EG' => 'EG', '1. OG' => '1']]);

        $source = $this->source(<<<'SVG'
          <g id="Umgebung"><rect class="st2" width="10" height="10"/></g>
          <g id="EG"><g id="V1"><path class="st0" d="M0 0h1"/><path class="st1" d="M0 0h1"/></g><g id="Gang1" data-name="Gang"><path class="st0" d="M0 0h1"/></g></g>
          <g id="_x31_._OG"><g id="V101"><path fill="#E0E9EB" d="M0 0h1"/></g></g>
        SVG);

        $this->artisan('isometry:build', ['source' => $source])
            ->expectsOutputToContain('Apartments: 2 – V.1, V.101')
            ->assertSuccessful();

        $svg = File::get($this->output);
        $this->assertStringStartsWith('<svg', $svg, 'no XML prolog');
        $this->assertStringContainsString('class="iso-svg"', $svg);
        $this->assertStringContainsString('data-isometry=""', $svg);
        $this->assertStringNotContainsString('width="400"', $svg);
        $this->assertStringNotContainsString('Illustrator', $svg);
        $this->assertStringContainsString('class="iso-base"', $svg);
        $this->assertStringContainsString('data-iso-floor="EG"', $svg);
        $this->assertStringContainsString('data-iso-floor="1"', $svg, '_x31_._OG decodes to "1. OG"');
        $this->assertStringContainsString('<g id="V1" data-iso="V.1">', $svg);
        $this->assertStringNotContainsString('data-iso="Gang', $svg);
        $this->assertStringContainsString('class="st0 iso-face"', $svg);
        $this->assertStringContainsString('class="st1 iso-top"', $svg);
        $this->assertStringContainsString('fill="#E0E9EB" d="M0 0h1" class="iso-top"', $svg, 'fill attribute counts too');

        File::delete($source);
    }

    public function test_missing_floor_fails(): void
    {
        config(['isometry.floors' => ['EG' => 'EG', '3. OG' => '3']]);
        $source = $this->source('<g id="EG"><g id="V1"><path class="st0" d="M0 0h1"/></g></g>');

        $this->artisan('isometry:build', ['source' => $source])
            ->expectsOutputToContain('missing: 3. OG')
            ->assertFailed();

        File::delete($source);
    }

    public function test_apartment_page_shows_the_drawing_with_its_ratio(): void
    {
        File::put($this->output, '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 200" class="iso-svg" data-isometry=""></svg>');
        config([
            'apartments.driver' => 'fake',
            'apartments.drivers.fake' => FakeApartmentSource::class,
            'testing.apartments' => [['ref' => 'A.01', 'status' => 'free', 'floor' => 'EG']],
        ]);

        $this->get(route('page.apartments'))
            ->assertOk()
            ->assertSee('data-isometry', false)
            ->assertSee('--iso-ratio: 2;', false);
    }

    public function test_starter_drawing_matches_the_demo_apartments(): void
    {
        $svg = File::get(base_path('resources/isometry/building.svg'));
        preg_match_all('/data-iso="([^"]+)"/', $svg, $m);

        // Without the static driver there is nothing to compare against.
        $expected = array_column(config('apartments-static') ?? [], 'ref') ?: $m[1];
        sort($expected);
        $found = $m[1];
        sort($found);

        $this->assertSame($expected, $found);
    }
}
