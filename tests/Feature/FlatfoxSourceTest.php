<?php

namespace Tests\Feature;

use App\Apartments\ApartmentRepository;
use App\Apartments\Sources\FlatfoxSource;
use App\Apartments\Status;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FlatfoxSourceTest extends TestCase
{
    private bool $apiDown = false;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        config(['apartments.driver' => 'flatfox', 'flatfox.api_url' => 'https://flatfox.test/api/v1/public-listing/']);

        Http::fake(function ($request) {
            if ($this->apiDown) {
                return Http::response('boom', 500);
            }

            $page = str_contains($request->url(), 'page=2') ? 2 : 1;

            return Http::response(file_get_contents(base_path("tests/fixtures/flatfox-page-{$page}.json")));
        });
    }

    public function test_it_follows_pages_and_normalises_listings(): void
    {
        $apartments = collect((new FlatfoxSource)->fetch())->keyBy('ref');

        // 5 listings, 01-001 twice (pk 9 removed, pk 10 active: the newer one wins).
        $this->assertCount(4, $apartments);

        $first = $apartments['01-001'];
        $this->assertSame(Status::Free, $first->status);
        $this->assertSame('EG', $first->floor);
        $this->assertSame(2.5, $first->rooms);
        $this->assertSame(2070, $first->rentGross);
        $this->assertSame('https://flatfox.test/plan-001.pdf', $first->planUrl);
        $this->assertSame('https://flatfox.ch/de/listing/abc1/submit/', $first->applyUrl);

        $this->assertSame(Status::Reserved, $apartments['01-002']->status);
        $this->assertSame('1', $apartments['01-002']->floor);
        $this->assertSame(Status::Rented, $apartments['02-001']->status, 'archived = rented');
        $this->assertSame('UG', $apartments['02-001']->floor);
        $this->assertSame('parking', $apartments['99-PP1']->type);
    }

    public function test_fetch_command_caches_and_page_lists_only_apartments(): void
    {
        $this->artisan('apartments:fetch')->assertSuccessful();

        $this->assertTrue(Storage::disk('local')->exists('apartments.json'));
        $this->assertSame(['01-001', '01-002', '02-001'], app(ApartmentRepository::class)->all()->pluck('ref')->all());
    }

    public function test_api_error_keeps_the_cached_data(): void
    {
        $this->artisan('apartments:fetch')->assertSuccessful();

        $this->apiDown = true;
        $this->artisan('apartments:fetch')->assertFailed();

        $this->assertCount(3, app(ApartmentRepository::class)->all());
    }

    public function test_it_is_scheduled(): void
    {
        $this->artisan('schedule:list')->expectsOutputToContain('apartments:fetch');
    }
}
