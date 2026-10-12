<?php

namespace Tests\Feature;

use App\Apartments\Apartment;
use App\Apartments\ApartmentRepository;
use App\Apartments\ApiSource;
use App\Apartments\Status;
use Illuminate\Support\Facades\Storage;
use Tests\Support\FakeApartmentSource;
use Tests\TestCase;

class ApartmentsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'apartments.driver' => 'fake',
            'apartments.drivers.fake' => FakeApartmentSource::class,
            'testing.apartments' => [
                ['ref' => 'A.01', 'house' => 'A', 'floor' => 'EG', 'rooms' => 2.5, 'area' => 61.2, 'rent_gross' => 2070, 'status' => 'free', 'plan_url' => '/downloads/a01.pdf'],
                ['ref' => 'A.11', 'house' => 'A', 'floor' => '1', 'rooms' => 4.5, 'area' => 108, 'rent_gross' => 3360, 'status' => 'rented'],
                ['ref' => 'B.01', 'house' => 'B', 'floor' => 'EG', 'rooms' => 3.5, 'area' => 84, 'rent_gross' => 2670, 'status' => 'reserved', 'type' => 'commercial'],
            ],
        ]);
    }

    public function test_page_renders_rows_in_source_order_with_configured_columns(): void
    {
        config(['apartments.types' => null, 'apartments.columns' => ['ref' => ['Nr.', 'Nr.'], 'area' => ['Wohnfläche m²', 'Fläche'], 'rent_gross' => ['Bruttomiete', 'Brutto'], 'status' => ['Status', 'Status']]]);

        $html = $this->get(route('page.apartments'))->assertOk()->getContent();

        preg_match_all('/data-apartment="([^"]+)"/', $html, $m);
        $this->assertSame(['A.01', 'A.11', 'B.01'], $m[1]);

        $this->assertStringContainsString('Wohnfläche m²', $html);
        $this->assertStringNotContainsString('Etage</th>', $html);
        $this->assertStringContainsString('2&#039;070', $html);
        $this->assertStringContainsString('>62<', preg_replace('/\s+/', '', $html), 'area rounds up');
    }

    public function test_rented_apartments_hide_their_rent(): void
    {
        $this->get(route('page.apartments'))
            ->assertDontSee('3&#039;360', false)
            ->assertSee('vermietet');

        config(['apartments.hide_prices_when_rented' => false]);
        $this->get(route('page.apartments'))->assertSee('3&#039;360', false);
    }

    public function test_types_filter_out_other_objects(): void
    {
        $refs = app(ApartmentRepository::class)->all()->pluck('ref')->all();

        $this->assertSame(['A.01', 'A.11'], $refs);
    }

    public function test_filter_options_only_offer_existing_values(): void
    {
        config(['apartments.types' => null, 'apartments.filters' => ['status', 'rooms', 'floor', 'house']]);
        $repository = app(ApartmentRepository::class);

        $filters = $repository->filterOptions($repository->all());

        $this->assertSame(['free' => 'frei', 'reserved' => 'reserviert', 'rented' => 'vermietet'], $filters['status']['options']);
        $this->assertSame(['2.5' => '2.5 Zimmer', '3.5' => '3.5 Zimmer', '4.5' => '4.5 Zimmer'], $filters['rooms']['options']);
        $this->assertSame(['EG' => 'EG', '1' => '1. OG'], $filters['floor']['options']);
        $this->assertSame(['A' => 'Haus A', 'B' => 'Haus B'], $filters['house']['options']);
    }

    public function test_grouping_by_house_renders_one_table_per_house(): void
    {
        config(['apartments.types' => null, 'apartments.group_by_house' => true, 'apartments.houses' => ['A' => 'Haus A – Musterweg 1']]);

        $this->get(route('page.apartments'))
            ->assertSee('Haus A – Musterweg 1')
            ->assertSee('Haus B');

        $this->assertSame(2, substr_count($this->get(route('page.apartments'))->getContent(), 'data-apartment-group'));
    }

    public function test_natural_sort_orders_by_house_floor_and_ref(): void
    {
        config(['apartments.types' => null, 'apartments.sort' => 'natural', 'testing.apartments' => [
            ['ref' => 'A.10', 'house' => 'A', 'floor' => '1', 'status' => 'free'],
            ['ref' => 'A.9', 'house' => 'A', 'floor' => '1', 'status' => 'free'],
            ['ref' => 'A.1', 'house' => 'A', 'floor' => 'EG', 'status' => 'free'],
        ]]);

        $this->assertSame(['A.1', 'A.9', 'A.10'], app(ApartmentRepository::class)->all()->pluck('ref')->all());
    }

    public function test_unknown_driver_fails_clearly(): void
    {
        config(['apartments.driver' => 'nope']);

        $this->expectExceptionMessage("Apartment driver 'nope' is not installed");
        app(ApartmentRepository::class)->all();
    }

    public function test_fetch_command_does_nothing_without_api(): void
    {
        $this->artisan('apartments:fetch')->assertSuccessful()->expectsOutputToContain('nothing to fetch');
    }

    public function test_status_labels_come_from_config(): void
    {
        config(['apartments.status_labels.rented' => 'verkauft']);

        $this->assertSame('verkauft', Status::Rented->label());
    }

    public function test_api_source_keeps_last_data_when_fetch_fails(): void
    {
        Storage::fake('local');

        $source = new class extends ApiSource
        {
            public array|\Throwable $next = [];

            public function fetch(): array
            {
                if ($this->next instanceof \Throwable) {
                    throw $this->next;
                }

                return $this->next;
            }
        };

        $source->next = [Apartment::fromArray(['ref' => 'X.1', 'status' => 'free'])];
        $this->assertSame(1, $source->refresh());

        $source->next = new \RuntimeException('API down');
        $this->assertNull($source->refresh());

        $source->next = [];
        $this->assertNull($source->refresh());

        $this->assertSame(['X.1'], array_map(fn ($a) => $a->ref, $source->all()));
    }
}
