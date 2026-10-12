<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Seiten-Tests sollen nicht von einem vorhandenen npm-Build abhaengen.
        $this->withoutVite();
    }
}
