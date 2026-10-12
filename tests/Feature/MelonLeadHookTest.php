<?php

namespace Tests\Feature;

use App\Livewire\ContactForm;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class MelonLeadHookTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        config([
            'melon-lead.enabled' => true,
            'melon-lead.url' => 'https://melon.test/de/external-data-hook',
            'melon-lead.username' => 'immoserver',
            'melon-lead.password' => 'geheim',
        ]);
    }

    private function submit(string $zipCity = '8048 Zürich'): void
    {
        Livewire::test(ContactForm::class)
            ->set('first_name', 'Anna')->set('last_name', 'Muster')
            ->set('street', 'Badenerstrasse 587')->set('zip_city', $zipCity)
            ->set('email', 'anna@example.com')->set('phone', '+41 44 123 45 67')
            ->set('message', 'Hallo')->set('privacy', true)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirect(route('page.thanks'));
    }

    public function test_contact_form_sends_the_lead(): void
    {
        Http::fake();

        $this->submit();

        Http::assertSent(fn ($request) => $request->url() === 'https://melon.test/de/external-data-hook'
            && $request->hasHeader('Authorization', 'Basic '.base64_encode('immoserver:geheim'))
            && $request->data() === [
                'firstname' => 'Anna',
                'name' => 'Muster',
                'street_nr' => 'Badenerstrasse 587',
                'postcode' => 8048,
                'city' => 'Zürich',
                'email' => 'anna@example.com',
                'phone' => '+41 44 123 45 67',
                'remarks' => 'Hallo',
                'howfound' => 'project_website',
            ]);
    }

    public function test_without_postcode_everything_goes_to_city(): void
    {
        Http::fake();

        $this->submit('Zürich');

        Http::assertSent(fn ($request) => ! array_key_exists('postcode', $request->data()) && $request->data()['city'] === 'Zürich');
    }

    public function test_unreachable_melon_does_not_block_the_form(): void
    {
        Http::fake(fn () => throw new ConnectionException('timeout'));

        $this->submit();
    }

    public function test_disabled_hook_sends_nothing(): void
    {
        Http::fake();
        config(['melon-lead.enabled' => false]);

        $this->submit();

        Http::assertNothingSent();
    }
}
