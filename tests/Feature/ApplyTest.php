<?php

namespace Tests\Feature;

use App\Livewire\ApplicationForm;
use App\Mail\ContactInquiry;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\Support\FakeApartmentSource;
use Tests\TestCase;

class ApplyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['apply.mode' => 'auto', 'apply.label' => 'Bewerben', 'apply.fields' => ['street' => 'required', 'zip_city' => 'required', 'phone' => 'required', 'message' => 'optional']]);

        config([
            'apartments.driver' => 'fake',
            'apartments.drivers.fake' => FakeApartmentSource::class,
            'testing.apartments' => [
                ['ref' => 'A.01', 'status' => 'free', 'apply_url' => 'https://flatfox.ch/de/listing/abc/submit/'],
                ['ref' => 'A.02', 'status' => 'reserved'],
                ['ref' => 'A.03', 'status' => 'rented'],
            ],
        ]);
    }

    public function test_auto_mode_links_to_source_or_own_form(): void
    {
        $html = $this->get(route('page.apartments'))->assertOk()->getContent();

        $this->assertStringContainsString('href="https://flatfox.ch/de/listing/abc/submit/"', $html);
        $this->assertStringContainsString('href="'.route('page.apply', ['wohnung' => 'A.02']).'"', $html);
        $this->assertStringNotContainsString('Wohnung A.03"', $html, 'rented apartments get no button');
        $this->assertStringContainsString('<span class="sr-only">Bewerbung</span>', $html);
    }

    public function test_form_mode_always_uses_own_form(): void
    {
        config(['apply.mode' => 'form']);

        $this->get(route('page.apartments'))
            ->assertDontSee('flatfox.ch/de/listing/abc', false)
            ->assertSee(route('page.apply', ['wohnung' => 'A.01']), false);
    }

    public function test_link_mode_shows_no_button_without_source_link(): void
    {
        config(['apply.mode' => 'link']);

        $this->get(route('page.apartments'))->assertDontSee(route('page.apply', ['wohnung' => 'A.02']), false);
    }

    public function test_application_mail_names_the_apartment(): void
    {
        Mail::fake();

        Livewire::withQueryParams(['wohnung' => 'A.02'])
            ->test(ApplicationForm::class)
            ->assertSee('Bewerbung für Wohnung A.02')
            ->set('first_name', 'Anna')->set('last_name', 'Muster')
            ->set('street', 'Weg 1')->set('zip_city', '8000 Zürich')
            ->set('email', 'anna@example.com')->set('phone', '044')
            ->set('message', 'Ich hätte gerne eine Besichtigung.')
            ->set('privacy', true)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirect(route('page.thanks'));

        Mail::assertSent(ContactInquiry::class, fn ($mail) => $mail->data['message'] === "Bewerbung für Wohnung A.02\n\nIch hätte gerne eine Besichtigung.");
    }

    public function test_apply_page_is_not_indexed(): void
    {
        $this->get(route('page.apply', ['wohnung' => 'A.01']))
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex">', false);
    }
}
