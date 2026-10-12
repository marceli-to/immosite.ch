<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class DownloadsTest extends TestCase
{
    public function test_existing_files_link_and_missing_files_show_pdf_follows(): void
    {
        $file = 'downloads/test-'.uniqid().'.pdf';
        File::put(public_path($file), '%PDF');

        config(['downloads.groups.test' => [['Vorhanden', $file], ['Fehlt', 'downloads/missing.pdf']]]);

        try {
            $html = Blade::render('<x-downloads.buttons group="test" />');
        } finally {
            File::delete(public_path($file));
        }

        $this->assertStringContainsString('href="'.asset($file).'"', $html);
        $this->assertStringContainsString('Fehlt <span class="font-normal">(PDF folgt)</span>', $html);
        $this->assertStringNotContainsString('missing.pdf', $html);
    }

    public function test_empty_group_renders_nothing(): void
    {
        $this->assertSame('', trim(Blade::render('<x-downloads.buttons group="nope" />')));
    }

    public function test_button_style_comes_from_config(): void
    {
        config(['downloads.button' => 'primary', 'downloads.groups.test' => [['Plan', 'favicon.ico']]]);

        $this->assertStringContainsString('bg-primary', Blade::render('<x-downloads.buttons group="test" />'));
    }
}
