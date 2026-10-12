<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class PictureTest extends TestCase
{
    private string $dir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dir = public_path('img/test-'.uniqid());
        File::ensureDirectoryExists($this->dir);

        $image = imagecreatetruecolor(300, 200);
        imagejpeg($image, "{$this->dir}/photo.jpg");
        imagewebp($image, "{$this->dir}/photo.webp");
        imagejpeg(imagecreatetruecolor(100, 100), "{$this->dir}/photo-mobile.jpg");
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->dir);

        parent::tearDown();
    }

    private function render(string $attributes = ''): string
    {
        $src = 'img/'.basename($this->dir).'/photo';

        return Blade::render("<x-media.picture src=\"{$src}\" alt=\"Foto\" {$attributes} />");
    }

    public function test_dimensions_come_from_the_file(): void
    {
        $html = $this->render();

        $this->assertStringContainsString('width="300" height="200"', $html);
        $this->assertStringContainsString('loading="lazy"', $html);
    }

    public function test_existing_variants_become_sources(): void
    {
        $html = $this->render();

        $this->assertStringContainsString('photo.webp" type="image/webp"', $html);
        $this->assertStringContainsString('photo-mobile.jpg" type="image/jpeg" media="(max-width: 767px)"', $html);
        $this->assertStringNotContainsString('.avif', $html);
    }

    public function test_priority_images_load_eagerly(): void
    {
        $html = $this->render(':priority="true"');

        $this->assertStringContainsString('fetchpriority="high"', $html);
        $this->assertStringNotContainsString('loading="lazy"', $html);
    }
}
