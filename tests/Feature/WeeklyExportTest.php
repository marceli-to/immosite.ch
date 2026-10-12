<?php

namespace Tests\Feature;

use App\Models\Registration;
use App\Notifications\WeeklyRegistrationsExport;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class WeeklyExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'registration-export.recipients' => 'vermietung@example.ch, chef@example.ch',
            'registration-export.bcc' => 'archiv@example.ch',
        ]);
    }

    private function registration(array $attributes = []): Registration
    {
        return Registration::create([
            'interests' => ['3.5', 'gewerbe'],
            'first_name' => 'Bea',
            'last_name' => 'Test',
            'street' => 'Weg 2',
            'zip_city' => '8048 Zürich',
            'email' => 'bea@example.com',
            'phone' => '044 000 00 00',
            ...$attributes,
        ]);
    }

    public function test_it_sends_new_registrations_and_marks_them_exported(): void
    {
        Notification::fake();
        $this->registration();
        $this->registration(['email' => 'old@example.com', 'exported_at' => now()->subWeek()]);

        $this->artisan('registrations:export-weekly')->assertSuccessful();

        Notification::assertSentOnDemand(
            WeeklyRegistrationsExport::class,
            function ($notification, $channels, AnonymousNotifiable $notifiable) {
                return $notification->registrations->pluck('email')->all() === ['bea@example.com']
                    && $notifiable->routes['mail'] === ['vermietung@example.ch', 'chef@example.ch'];
            },
        );
        $this->assertSame(0, Registration::whereNull('exported_at')->count());
    }

    public function test_a_second_run_sends_an_empty_list(): void
    {
        Notification::fake();
        $this->registration(['exported_at' => now()]);

        $this->artisan('registrations:export-weekly')->assertSuccessful();

        Notification::assertSentOnDemand(
            WeeklyRegistrationsExport::class,
            fn ($notification) => $notification->registrations->isEmpty(),
        );
    }

    public function test_all_option_resends_everything(): void
    {
        Notification::fake();
        $this->registration(['exported_at' => now()]);

        $this->artisan('registrations:export-weekly', ['--all' => true])->assertSuccessful();

        Notification::assertSentOnDemand(
            WeeklyRegistrationsExport::class,
            fn ($notification) => $notification->registrations->count() === 1,
        );
    }

    public function test_it_fails_without_recipient(): void
    {
        Notification::fake();
        config(['registration-export.recipients' => '']);

        $this->artisan('registrations:export-weekly')->assertFailed();

        Notification::assertNothingSent();
    }

    public function test_failed_send_keeps_registrations_for_next_week(): void
    {
        $this->registration();
        Notification::shouldReceive('send')->andThrow(new \RuntimeException('SMTP down'));

        try {
            $this->artisan('registrations:export-weekly');
        } catch (\RuntimeException) {
        }

        $this->assertSame(1, Registration::whereNull('exported_at')->count());
    }

    public function test_mail_has_bcc_and_csv_with_labels(): void
    {
        $registration = $this->registration(['message' => 'Hallo; "Test"']);
        $notification = new WeeklyRegistrationsExport(collect([$registration]), now()->subWeek(), now());

        $mail = $notification->toMail(new AnonymousNotifiable);

        $this->assertSame('archiv@example.ch', $mail->bcc[0][0]);
        $this->assertCount(1, $mail->rawAttachments);

        $csv = $notification->csv();
        $this->assertStringStartsWith("\xEF\xBB\xBF", $csv);
        $this->assertStringContainsString('3.5-Zimmerwohnung, Gewerbefläche', $csv);
        $this->assertStringContainsString('"Hallo; ""Test"""', $csv);

        $html = (string) $mail->render();
        $this->assertStringContainsString('Bea Test', $html);
    }

    public function test_it_is_scheduled_from_config(): void
    {
        $event = collect(app(Schedule::class)->events())
            ->first(fn ($event) => str_contains($event->command, 'registrations:export-weekly'));

        $this->assertNotNull($event);
        $this->assertSame('0 8 * * 1', $event->expression);
        $this->assertSame('Europe/Zurich', $event->timezone);
    }
}
