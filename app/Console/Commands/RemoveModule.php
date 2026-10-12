<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Removes a module based on modules.md: deletes its files, removes hook lines
 * (marked with module:<name>) and drops its section from modules.md.
 * Modules that need the removed module (needs:) are removed too.
 *
 * Hooks are always exactly one line. A line owned by several modules
 * (module:a,b) stays as long as one of them is still installed.
 */
class RemoveModule extends Command
{
    protected $signature = 'module:remove
        {names* : One or more modules}
        {--path= : Project directory (default: this project)}
        {--dry-run : Only show what would happen, change nothing}';

    protected $description = 'Removes modules (files, hooks, manifest entry) based on modules.md';

    /** Directories searched for hook lines. */
    private const HOOK_DIRS = ['app', 'bootstrap', 'config', 'database', 'resources', 'routes', 'tests'];

    /** @var array<string, array<int, string>> PHP files of module directories, collected before deletion */
    private array $directoryFiles = [];

    private const HOOK_EXTENSIONS = ['php', 'js', 'css'];

    public function handle(): int
    {
        $root = rtrim($this->option('path') ?: base_path(), '/');
        $manifestPath = "{$root}/modules.md";

        if (! File::exists($manifestPath)) {
            $this->error("modules.md not found in {$root}");

            return self::FAILURE;
        }

        $manifest = File::get($manifestPath);
        $modules = self::parse($manifest);

        foreach ($this->argument('names') as $name) {
            if (! isset($modules[$name])) {
                $this->error("Module '{$name}' is not listed in modules.md (installed: ".implode(', ', array_keys($modules)).')');

                return self::FAILURE;
            }
        }

        $remove = $this->withDependents($this->argument('names'), $modules);
        $dry = $this->option('dry-run');

        $this->info(($dry ? '[dry-run] ' : '').'Removing: '.implode(', ', $remove));

        foreach ($remove as $name) {
            foreach ($modules[$name]['files'] as $file) {
                $path = "{$root}/{$file}";
                if (! File::exists($path)) {
                    $this->warn("  already missing: {$file}");

                    continue;
                }
                $this->line("  - {$file}");
                // Remember the PHP files of a directory before it is deleted (for removeImports).
                if (File::isDirectory($path)) {
                    $this->directoryFiles[$file] = array_map(fn ($f) => str_replace("{$root}/", '', $f->getPathname()), File::allFiles($path));
                }
                if (! $dry) {
                    File::isDirectory($path) ? File::deleteDirectory($path) : File::delete($path);
                }
            }
        }

        $this->removeHooks($root, $remove, array_diff(array_keys($modules), $remove), $dry);
        $this->removeImports($root, $this->deletedClasses($remove, $modules), $dry);
        $this->removePackages($root, $remove, $modules, $dry);

        if (! $dry) {
            File::put($manifestPath, self::withoutSections($manifest, $remove));
        }

        foreach ($remove as $name) {
            if ($env = $modules[$name]['env']) {
                $this->line("  Check config/env of {$name} (.env, .env.example): ".implode(', ', $env));
            }
        }

        return self::SUCCESS;
    }

    /**
     * @return array<string, array{needs: array<int,string>, files: array<int,string>, env: array<int,string>, packages: array<int,string>}>
     */
    public static function parse(string $manifest): array
    {
        $modules = [];
        $current = null;
        $block = null;

        foreach (preg_split('/\R/', $manifest) as $line) {
            if (preg_match('/^## ([a-z0-9-]+) \(optional(?:, needs: ([a-z0-9, -]+))?\)/', $line, $m)) {
                $current = $m[1];
                $block = null;
                $modules[$current] = [
                    'needs' => isset($m[2]) ? array_map('trim', explode(',', $m[2])) : [],
                    'files' => [],
                    'env' => [],
                    'packages' => [],
                ];

                continue;
            }

            if (str_starts_with($line, '## ')) {
                $current = null;

                continue;
            }

            if ($current === null) {
                continue;
            }

            if (preg_match('/^(Files|Hooks|Config\/env|Packages):/', $line, $m)) {
                $block = $m[1];

                continue;
            }

            if (preg_match('/^- (.+)$/', $line, $m) && trim($m[1]) !== '–') {
                if ($block === 'Files') {
                    $modules[$current]['files'][] = trim($m[1]);
                } elseif ($block === 'Packages') {
                    $modules[$current]['packages'][] = trim($m[1]);
                } elseif ($block === 'Config/env') {
                    foreach (preg_split('/,\s*/', $m[1]) as $key) {
                        $modules[$current]['env'][] = trim($key);
                    }
                }
            }
        }

        return $modules;
    }

    /** Requested modules plus all modules that depend on them (also indirectly). */
    private function withDependents(array $names, array $modules): array
    {
        $remove = $names;

        do {
            $added = false;
            foreach ($modules as $name => $module) {
                if (! in_array($name, $remove, true) && array_intersect($module['needs'], $remove)) {
                    $remove[] = $name;
                    $added = true;
                }
            }
        } while ($added);

        return $remove;
    }

    private function removeHooks(string $root, array $remove, array $installed, bool $dry): void
    {
        foreach (self::HOOK_DIRS as $dir) {
            if (! File::isDirectory("{$root}/{$dir}")) {
                continue;
            }

            foreach (File::allFiles("{$root}/{$dir}") as $file) {
                if (! in_array($file->getExtension(), self::HOOK_EXTENSIONS, true)) {
                    continue;
                }

                $content = $file->getContents();
                if (! str_contains($content, 'module:')) {
                    continue;
                }

                $changed = false;
                $lines = preg_split('/(?<=\n)/', $content);

                foreach ($lines as $i => $line) {
                    if (! preg_match('/module:([a-z0-9-]+(?:,[a-z0-9-]+)*)/', $line, $m)) {
                        continue;
                    }

                    $owners = explode(',', $m[1]);
                    $left = array_values(array_diff($owners, $remove));

                    if ($left === $owners) {
                        continue;
                    }

                    $changed = true;
                    $relative = str_replace("{$root}/", '', $file->getPathname());

                    // Stays only if a co-owner is installed. Names of modules that are not
                    // installed don't count (e.g. contact-form, which is added later
                    // and then brings the line along itself).
                    if (! array_intersect($left, $installed)) {
                        $this->line("  - Hook {$relative}: ".trim($line));
                        unset($lines[$i]);
                    } else {
                        $lines[$i] = str_replace('module:'.$m[1], 'module:'.implode(',', $left), $line);
                    }
                }

                if ($changed && ! $dry) {
                    File::put($file->getPathname(), implode('', $lines));
                }
            }
        }
    }

    /**
     * Removes a module's packages ("npm: name" / "composer: vendor/name") from package.json or
     * composer.json. The lockfiles are only updated by a later npm install / composer update.
     */
    private function removePackages(string $root, array $remove, array $modules, bool $dry): void
    {
        $files = ['npm' => ['package.json', ['dependencies', 'devDependencies']], 'composer' => ['composer.json', ['require', 'require-dev']]];
        $touched = [];

        foreach ($remove as $name) {
            foreach ($modules[$name]['packages'] as $entry) {
                [$manager, $package] = array_map('trim', explode(':', $entry, 2));
                [$file, $sections] = $files[$manager] ?? [null, []];
                $path = "{$root}/{$file}";

                if (! $file || ! File::exists($path)) {
                    continue;
                }

                $json = json_decode(File::get($path), true);
                foreach ($sections as $section) {
                    unset($json[$section][$package]);
                }

                $this->line("  - Package {$manager}: {$package}");
                $touched[$manager] = true;

                if (! $dry) {
                    $indent = $manager === 'npm' ? 2 : 4;
                    $encoded = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                    File::put($path, preg_replace_callback('/^ +/m', fn ($m) => str_repeat(' ', strlen($m[0]) / 4 * $indent), $encoded)."\n");
                }
            }
        }

        if (isset($touched['npm'])) {
            $this->line('  Then run: npm install');
        }
        if (isset($touched['composer'])) {
            $this->line('  Then run: composer update');
        }
    }

    /**
     * Classes (App\\…) defined in the module's files, including files inside listed directories.
     *
     * @return array<int, string>
     */
    private function deletedClasses(array $remove, array $modules): array
    {
        $classes = [];

        foreach ($remove as $name) {
            foreach ($modules[$name]['files'] as $file) {
                if (! str_starts_with($file, 'app/')) {
                    continue;
                }

                $paths = str_ends_with($file, '.php') ? [$file] : ($this->directoryFiles[$file] ?? []);

                foreach ($paths as $path) {
                    $classes[] = 'App\\'.str_replace('/', '\\', substr($path, 4, -4));
                }
            }
        }

        return $classes;
    }

    /**
     * Pint turns fully qualified class names into `use` imports, which carry no
     * module marker. Remove imports of classes whose files were deleted.
     */
    private function removeImports(string $root, array $classes, bool $dry): void
    {
        if ($classes === []) {
            return;
        }

        foreach (self::HOOK_DIRS as $dir) {
            if (! File::isDirectory("{$root}/{$dir}")) {
                continue;
            }

            foreach (File::allFiles("{$root}/{$dir}") as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $content = $file->getContents();
                $updated = preg_replace_callback('/^use ([^;\s]+);[^\n]*\n/m', function ($m) use ($classes, $file, $root) {
                    if (! in_array($m[1], $classes, true)) {
                        return $m[0];
                    }

                    $this->line('  - Import '.str_replace("{$root}/", '', $file->getPathname()).': use '.$m[1].';');

                    return '';
                }, $content);

                if ($updated !== $content && ! $dry) {
                    File::put($file->getPathname(), $updated);
                }
            }
        }
    }

    private static function withoutSections(string $manifest, array $remove): string
    {
        $out = [];
        $skip = false;

        foreach (preg_split('/(?<=\n)/', $manifest) as $line) {
            if (str_starts_with($line, '## ')) {
                $skip = (bool) preg_match('/^## ([a-z0-9-]+) \(/', $line, $m) && in_array($m[1], $remove, true);
            } elseif ($skip && str_starts_with($line, '---')) {
                continue;
            }

            if (! $skip) {
                $out[] = $line;
            }
        }

        return rtrim(implode('', $out))."\n";
    }
}
