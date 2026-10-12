<?php

namespace Tests\Feature;

use App\Models\CommercialObject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommercialAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['commercial.objects' => [
            ['ref' => 'G.01', 'floor' => 'EG', 'area' => 203, 'rent_net' => 5400, 'charges' => 600, 'status' => 'free'],
            ['ref' => 'G.02', 'floor' => 'UG', 'area' => 367, 'status' => 'reserved'],
        ]]);

        $this->artisan('commercial:seed')->assertSuccessful();
    }

    public function test_seed_copies_config_once(): void
    {
        $this->assertSame(['G.01', 'G.02'], CommercialObject::orderBy('position')->pluck('ref')->all());

        CommercialObject::where('ref', 'G.01')->update(['status' => 'rented']);
        $this->artisan('commercial:seed')->assertSuccessful();

        $this->assertSame('rented', CommercialObject::where('ref', 'G.01')->value('status'), 'existing data is not overwritten');
    }

    public function test_guests_are_sent_to_login(): void
    {
        $this->get(route('admin.objects.index'))->assertRedirect(route('login'));
        $this->get(route('login'))->assertOk()->assertSee('noindex, nofollow', false);
    }

    public function test_login_is_rate_limited(): void
    {
        User::factory()->create(['email' => 'vermietung@example.ch']);

        foreach (range(1, 5) as $attempt) {
            $this->post(route('admin.login'), ['email' => 'vermietung@example.ch', 'password' => 'falsch'])->assertSessionHasErrors('email');
        }

        $this->post(route('admin.login'), ['email' => 'vermietung@example.ch', 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertStringStartsWith('Zu viele Versuche.', session('errors')->first('email'));
        $this->assertGuest();
    }

    public function test_marketer_updates_status_and_rent(): void
    {
        $user = User::factory()->create(['name' => 'Erika']);
        $g1 = CommercialObject::where('ref', 'G.01')->first();
        $g2 = CommercialObject::where('ref', 'G.02')->first();

        $this->actingAs($user)
            ->put(route('admin.objects.update'), ['objects' => [
                $g1->id => ['status' => 'rented', 'rent_net' => 5400, 'charges' => 600],
                $g2->id => ['status' => 'reserved', 'rent_net' => 7000, 'charges' => null],
            ]])
            ->assertRedirect(route('admin.objects.index'))
            ->assertSessionHas('status', '2 Objekte gespeichert.');

        $this->assertSame('rented', $g1->fresh()->status);
        $this->assertSame(7000, $g2->fresh()->rent_net);
        $this->assertSame('Erika', $g2->fresh()->updated_by);
    }

    public function test_invalid_status_is_rejected(): void
    {
        $g1 = CommercialObject::where('ref', 'G.01')->first();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.objects.update'), ['objects' => [$g1->id => ['status' => 'verkauft']]])
            ->assertSessionHasErrors('objects.'.$g1->id.'.status');
    }

    public function test_database_source_feeds_the_public_page(): void
    {
        config(['commercial.source' => 'database']);
        CommercialObject::where('ref', 'G.01')->update(['status' => 'rented']);

        $this->get(route('page.commercial'))
            ->assertOk()
            ->assertSeeInOrder(['data-apartment="G.01"', 'vermietet', 'data-apartment="G.02"'], false)
            ->assertDontSee('5&#039;400', false);
    }

    public function test_user_create_command(): void
    {
        $this->artisan('user:create', ['--name' => 'Erika', '--email' => 'erika@example.ch'])
            ->expectsQuestion('Password (min. 10 characters)', 'ein-langes-passwort')
            ->assertSuccessful();

        $this->assertDatabaseHas('users', ['email' => 'erika@example.ch', 'name' => 'Erika']);
    }
}
