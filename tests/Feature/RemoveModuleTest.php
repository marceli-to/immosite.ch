<?php

namespace Tests\Feature;

use App\Console\Commands\RemoveModule;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class RemoveModuleTest extends TestCase
{
    private string $root;

    protected function setUp(): void
    {
        parent::setUp();

        $this->root = sys_get_temp_dir().'/immosite-module-test-'.uniqid();

        File::put($this->path('modules.md', true), <<<'MD'
        # Module

        ## Core (immer vorhanden)
        Nichts.

        ---

        ## alpha (optional)
        Files:
        - app/Alpha.php
        - resources/views/alpha
        Hooks:
        - routes/web.php → Route
        Config/env:
        - ALPHA_KEY, ALPHA_URL

        ---

        ## beta (optional, needs: alpha)
        Files:
        - app/Beta.php
        Config/env:
        - –

        ---

        ## gamma (optional)
        Files:
        - app/Gamma.php
        MD);

        File::put($this->path('app/Alpha.php', true), '<?php');
        File::put($this->path('app/Beta.php', true), '<?php');
        File::put($this->path('app/Gamma.php', true), '<?php');
        File::put($this->path('resources/views/alpha/view.blade.php', true), 'x');
        File::put($this->path('routes/web.php', true), implode("\n", [
            '<?php',
            "Route::view('/a', 'a'); // module:alpha",
            "Route::view('/g', 'g'); // module:gamma",
            "Route::view('/ag', 'ag'); // module:alpha,gamma",
            "Route::view('/ax', 'ax'); // module:alpha,not-installed",
            "Route::view('/core', 'core');",
            '',
        ]));
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->root);

        parent::tearDown();
    }

    private function path(string $relative, bool $create = false): string
    {
        $path = "{$this->root}/{$relative}";
        if ($create) {
            File::ensureDirectoryExists(dirname($path));
        }

        return $path;
    }

    public function test_it_parses_the_manifest(): void
    {
        $modules = RemoveModule::parse(File::get($this->path('modules.md')));

        $this->assertSame(['alpha', 'beta', 'gamma'], array_keys($modules));
        $this->assertSame(['alpha'], $modules['beta']['needs']);
        $this->assertSame(['app/Alpha.php', 'resources/views/alpha'], $modules['alpha']['files']);
        $this->assertSame(['ALPHA_KEY', 'ALPHA_URL'], $modules['alpha']['env']);
        $this->assertSame([], $modules['beta']['env']);
    }

    public function test_it_removes_files_hooks_dependents_and_manifest_sections(): void
    {
        $this->artisan('module:remove', ['names' => ['alpha'], '--path' => $this->root])
            ->assertSuccessful();

        $this->assertFileDoesNotExist($this->path('app/Alpha.php'));
        $this->assertDirectoryDoesNotExist($this->path('resources/views/alpha'));
        $this->assertFileDoesNotExist($this->path('app/Beta.php'), 'beta braucht alpha und muss mit');
        $this->assertFileExists($this->path('app/Gamma.php'));

        $routes = File::get($this->path('routes/web.php'));
        $this->assertStringNotContainsString("'/a'", $routes);
        $this->assertStringContainsString("Route::view('/g', 'g'); // module:gamma", $routes);
        $this->assertStringContainsString("Route::view('/ag', 'ag'); // module:gamma", $routes, 'geteilte Zeile bleibt fuer gamma');
        $this->assertStringContainsString("Route::view('/core', 'core');", $routes);
        $this->assertStringNotContainsString("'/ax'", $routes, 'Mitbesitzer, der nicht installiert ist, haelt die Zeile nicht');

        $manifest = File::get($this->path('modules.md'));
        $this->assertStringNotContainsString('## alpha', $manifest);
        $this->assertStringNotContainsString('## beta', $manifest);
        $this->assertStringContainsString('## gamma', $manifest);
        $this->assertStringContainsString('## Core', $manifest);
    }

    public function test_shared_hook_goes_when_all_owners_are_removed(): void
    {
        $this->artisan('module:remove', ['names' => ['alpha', 'gamma'], '--path' => $this->root])
            ->assertSuccessful();

        $routes = File::get($this->path('routes/web.php'));
        $this->assertStringNotContainsString('module:', $routes);
        $this->assertStringContainsString("Route::view('/core', 'core');", $routes);
    }

    public function test_dry_run_changes_nothing(): void
    {
        $before = File::get($this->path('routes/web.php'));

        $this->artisan('module:remove', ['names' => ['alpha'], '--path' => $this->root, '--dry-run' => true])
            ->assertSuccessful();

        $this->assertFileExists($this->path('app/Alpha.php'));
        $this->assertSame($before, File::get($this->path('routes/web.php')));
    }

    public function test_unknown_module_fails(): void
    {
        $this->artisan('module:remove', ['names' => ['nope'], '--path' => $this->root])
            ->assertFailed();
    }

    public function test_the_real_manifest_lists_only_existing_files(): void
    {
        foreach (RemoveModule::parse(File::get(base_path('modules.md'))) as $name => $module) {
            foreach ($module['files'] as $file) {
                $this->assertFileExists(base_path($file), "modules.md ({$name}) nennt eine fehlende Datei");
            }
        }
    }
}
