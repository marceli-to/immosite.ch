<?php

namespace Tests\Feature;

use App\Livewire\ContactForm;
use App\Mail\ContactConfirmation;
use App\Mail\ContactInquiry;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    private function filled(): Testable
    {
        return Livewire::test(ContactForm::class)
            ->set('first_name', 'Anna')
            ->set('last_name', 'Muster')
            ->set('street', 'Teststrasse 1')
            ->set('zip_city', '8048 Zürich')
            ->set('email', 'anna@example.com')
            ->set('phone', '044 123 45 67')
            ->set('message', "Zwei Zeilen\nText")
            ->set('privacy', true);
    }

    public function test_home_page_shows_the_form(): void
    {
        $this->get(route('page.home'))->assertOk()->assertSeeLivewire(ContactForm::class);
    }

    public function test_it_mails_the_marketer_and_the_sender_then_redirects(): void
    {
        Mail::fake();
        config(['contact.recipients' => 'a@example.ch, b@example.ch']);

        $this->filled()
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirect(route('page.thanks'));

        Mail::assertSent(ContactInquiry::class, function (ContactInquiry $mail) {
            return $mail->hasTo('a@example.ch') && $mail->hasTo('b@example.ch')
                && $mail->hasReplyTo('anna@example.com')
                && $mail->data['message'] === "Zwei Zeilen\nText";
        });
        Mail::assertSent(ContactConfirmation::class, fn ($mail) => $mail->hasTo('anna@example.com'));
    }

    public function test_recipients_fall_back_to_the_marketer(): void
    {
        config(['contact.recipients' => null, 'site.marketer.email' => 'vermietung@example.ch']);

        $this->assertSame(['vermietung@example.ch'], ContactForm::recipients());
    }

    public function test_it_validates_required_fields(): void
    {
        Mail::fake();

        Livewire::test(ContactForm::class)
            ->call('submit')
            ->assertHasErrors(['first_name', 'last_name', 'street', 'zip_city', 'email', 'phone', 'privacy'])
            ->assertHasNoErrors('message');

        Mail::assertNothingSent();
    }

    public function test_failed_marketer_mail_shows_an_error_and_keeps_the_input(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));

        $this->filled()
            ->call('submit')
            ->assertHasErrors('send')
            ->assertNoRedirect()
            ->assertSet('first_name', 'Anna');
    }

    public function test_honeypot_redirects_without_mail(): void
    {
        Mail::fake();

        $this->filled()
            ->set('website', 'https://spam.example')
            ->call('submit')
            ->assertRedirect(route('page.thanks'));

        Mail::assertNothingSent();
    }

    public function test_failed_turnstile_check_sends_nothing(): void
    {
        Mail::fake();
        config(['services.turnstile.secret_key' => 'test-secret']);
        Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => false])]);

        $this->filled()->call('submit')->assertHasErrors('turnstileToken');

        Mail::assertNothingSent();
    }

    public function test_inquiry_mail_lists_only_filled_fields(): void
    {
        $html = (new ContactInquiry([
            'first_name' => 'Anna', 'last_name' => 'Muster', 'email' => 'anna@example.com',
            'street' => null, 'zip_city' => null, 'phone' => '044', 'message' => '<b>Hallo</b>',
        ]))->render();

        $this->assertStringContainsString('Anna', $html);
        $this->assertStringContainsString('Telefon', $html);
        $this->assertStringNotContainsString('Strasse/Nr.', $html);
        $this->assertStringContainsString('&lt;b&gt;Hallo&lt;/b&gt;', $html);
    }

    public function test_thanks_page_is_not_indexed_and_not_in_the_sitemap(): void
    {
        $this->get(route('page.thanks'))
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex">', false);

        $this->get('/sitemap.xml')->assertDontSee(route('page.thanks'));
    }
}
