<?php

namespace Tests\Feature;

use Tests\TestCase;

class PatternsTest extends TestCase
{
    public function test_every_pattern_renders(): void
    {
        $html = view('patterns.index')->render();

        foreach (glob(resource_path('views/patterns/*.blade.php')) as $file) {
            $name = basename($file, '.blade.php');
            if ($name !== 'index') {
                $this->assertStringContainsString("id=\"muster-{$name}\"", $html);
            }
        }
    }

    public function test_route_exists_only_locally(): void
    {
        $this->get('/muster')->assertNotFound();
    }
}
