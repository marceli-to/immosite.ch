<?php

namespace Tests\Feature;

use App\Livewire\RegistrationForm;
use App\Mail\RegistrationConfirmation;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

class RegistrationFormTest extends TestCase
{
    use RefreshDatabase;

    private function filled(): Testable
    {
        return Livewire::test(RegistrationForm::class)
            ->set('interests', ['2.5', 'gewerbe'])
            ->set('first_name', 'Anna')
            ->set('last_name', 'Muster')
            ->set('street', 'Teststrasse 1')
            ->set('zip_city', '8048 Zürich')
            ->set('email', 'anna@example.com')
            ->set('phone', '044 123 45 67')
            ->set('privacy', true);
    }

    public function test_home_page_shows_the_form(): void
    {
        $this->get(route('page.home'))
            ->assertOk()
            ->assertSeeLivewire(RegistrationForm::class);
    }

    public function test_it_stores_a_valid_registration_and_sends_a_confirmation(): void
    {
        Mail::fake();

        $this->filled()
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('submitted', true)
            ->assertSet('first_name', '');

        $registration = Registration::sole();
        $this->assertSame(['2.5', 'gewerbe'], $registration->interests);
        $this->assertSame(['2.5-Zimmerwohnung', 'Gewerbefläche'], $registration->interestLabels());
        $this->assertSame('Anna Muster', $registration->fullName());

        Mail::assertSent(RegistrationConfirmation::class, fn ($mail) => $mail->hasTo('anna@example.com'));
    }

    public function test_it_validates_required_fields(): void
    {
        Livewire::test(RegistrationForm::class)
            ->call('submit')
            ->assertHasErrors([
                'interests' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'street' => 'required',
                'zip_city' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'privacy' => 'accepted',
            ]);

        $this->assertSame(0, Registration::count());
    }

    public function test_it_rejects_unknown_interest_values(): void
    {
        $this->filled()
            ->set('interests', ['villa'])
            ->call('submit')
            ->assertHasErrors('interests.0');
    }

    public function test_fields_follow_config(): void
    {
        config([
            'registration.interests' => [],
            'registration.fields' => ['street' => false, 'zip_city' => false, 'phone' => 'optional', 'message' => 'required'],
        ]);

        Livewire::test(RegistrationForm::class)
            ->assertDontSee('Ich interessiere mich für')
            ->assertDontSee('Strasse/Nr.')
            ->assertSee('placeholder="Telefon"', false)
            ->assertSee('placeholder="Nachricht*"', false)
            ->call('submit')
            ->assertHasErrors(['message' => 'required'])
            ->assertHasNoErrors(['interests', 'street', 'zip_city', 'phone']);
    }

    public function test_honeypot_pretends_success_without_saving(): void
    {
        Mail::fake();

        $this->filled()
            ->set('website', 'https://spam.example')
            ->call('submit')
            ->assertSet('submitted', true);

        $this->assertSame(0, Registration::count());
        Mail::assertNothingSent();
    }

    public function test_failed_turnstile_check_blocks_the_registration(): void
    {
        config(['services.turnstile.secret_key' => 'test-secret']);
        Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => false])]);

        $this->filled()
            ->set('turnstileToken', 'bad-token')
            ->call('submit')
            ->assertHasErrors('turnstileToken')
            ->assertSet('submitted', false);

        $this->assertSame(0, Registration::count());
    }

    public function test_passed_turnstile_check_stores_the_registration(): void
    {
        Mail::fake();
        config(['services.turnstile.secret_key' => 'test-secret']);
        Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => true])]);

        $this->filled()
            ->set('turnstileToken', 'good-token')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertSame(1, Registration::count());
    }

    public function test_failing_confirmation_mail_still_counts_as_success(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));

        $this->filled()
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('submitted', true);

        $this->assertSame(1, Registration::count());
    }

    public function test_it_redirects_when_a_route_is_configured(): void
    {
        Mail::fake();
        config(['registration.redirect_route' => 'page.imprint']);

        $this->filled()
            ->call('submit')
            ->assertRedirect(route('page.imprint'));
    }

    public function test_privacy_page_describes_the_form(): void
    {
        $this->get(route('page.privacy'))->assertSee('Kontakt- und Anmeldeformular');
    }
}
