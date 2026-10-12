<?php

namespace Tests\Feature;

use App\Apartments\ApartmentRepository;
use App\Apartments\Sources\MelonSource;
use App\Apartments\Status;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MelonSourceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        config(['apartments.driver' => 'melon']);
    }

    public function test_mock_is_normalised(): void
    {
        config(['melon.mock' => true]);

        $apartments = collect((new MelonSource)->fetch())->keyBy('ref');

        $this->assertSame(Status::Free, $apartments['1.01']->status);
        $this->assertSame(Status::Reserved, $apartments['1.02']->status);
        $this->assertSame(Status::Rented, $apartments['1.11']->status);
        $this->assertNull($apartments['1.01']->planUrl, 'Melon "#" means no file');
        $this->assertNull($apartments['1.11']->applyUrl);
        $this->assertSame(2000, $apartments['1.01']->rentNet);
        $this->assertSame(38.0, $apartments['1.21']->outdoorArea);
        $this->assertSame('apartment', $apartments['1.21']->type);
        $this->assertSame('commercial', $apartments['G01']->type);
    }

    public function test_live_api_is_fetched_with_the_configured_url(): void
    {
        config(['melon.mock' => false, 'melon.api_url' => 'https://melon.test/v2/objects']);
        Http::fake(['melon.test/*' => Http::response(file_get_contents(database_path('data/melon-mock.json')))]);

        $this->artisan('apartments:fetch')->assertSuccessful();

        $this->assertSame(['1.01', '1.02', '1.11', '1.21'], app(ApartmentRepository::class)->all()->pluck('ref')->all());
    }

    public function test_missing_url_keeps_the_page_working(): void
    {
        config(['melon.mock' => false, 'melon.api_url' => null]);

        $this->get(route('page.apartments'))->assertOk()->assertSee('Keine Wohnungen gefunden');
    }
}
