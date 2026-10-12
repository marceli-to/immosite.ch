<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Entfernt ein Modul anhand von modules.md: Dateien loeschen, Hook-Zeilen
 * (markiert mit module:<name>) entfernen, Abschnitt aus modules.md streichen.
 * Module, die das entfernte Modul brauchen (needs:), gehen mit.
 *
 * Hooks sind immer genau eine Zeile. Eine Zeile mit mehreren Modulen
 * (module:a,b) bleibt, solange eines davon noch installiert ist.
 */
class RemoveModule extends Command
{
    protected $signature = 'module:remove
        {names* : Ein oder mehrere Module}
        {--path= : Projektverzeichnis (Standard: dieses Projekt)}
        {--dry-run : Nur anzeigen, nichts aendern}';

    protected $description = 'Entfernt Module (Dateien, Hooks, Manifest-Eintrag) anhand von modules.md';

    /** Ordner, in denen Hook-Zeilen gesucht werden. */
    private const HOOK_DIRS = ['app', 'bootstrap', 'config', 'database', 'resources', 'routes', 'tests'];

    private const HOOK_EXTENSIONS = ['php', 'js', 'css'];

    public function handle(): int
    {
        $root = rtrim($this->option('path') ?: base_path(), '/');
        $manifestPath = "{$root}/modules.md";

        if (! File::exists($manifestPath)) {
            $this->error("modules.md nicht gefunden in {$root}");

            return self::FAILURE;
        }

        $manifest = File::get($manifestPath);
        $modules = self::parse($manifest);

        foreach ($this->argument('names') as $name) {
            if (! isset($modules[$name])) {
                $this->error("Modul '{$name}' steht nicht in modules.md (installiert: ".implode(', ', array_keys($modules)).')');

                return self::FAILURE;
            }
        }

        $remove = $this->withDependents($this->argument('names'), $modules);
        $dry = $this->option('dry-run');

        $this->info(($dry ? '[dry-run] ' : '').'Entferne: '.implode(', ', $remove));

        foreach ($remove as $name) {
            foreach ($modules[$name]['files'] as $file) {
                $path = "{$root}/{$file}";
                if (! File::exists($path)) {
                    $this->warn("  fehlt bereits: {$file}");

                    continue;
                }
                $this->line("  - {$file}");
                if (! $dry) {
                    File::isDirectory($path) ? File::deleteDirectory($path) : File::delete($path);
                }
            }
        }

        $this->removeHooks($root, $remove, array_diff(array_keys($modules), $remove), $dry);
        $this->removePackages($root, $remove, $modules, $dry);

        if (! $dry) {
            File::put($manifestPath, self::withoutSections($manifest, $remove));
        }

        foreach ($remove as $name) {
            if ($env = $modules[$name]['env']) {
                $this->line("  Config/env von {$name} pruefen (.env, .env.example): ".implode(', ', $env));
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

    /** Gewuenschte Module plus alle, die (auch indirekt) davon abhaengen. */
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

                    // Bleibt nur, wenn ein Mitbesitzer installiert ist. Namen von nicht
                    // installierten Modulen zaehlen nicht (z.B. contact-form, das erst
                    // spaeter hinzukommt und die Zeile dann selbst mitbringt).
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
     * Pakete eines Moduls ("npm: name" / "composer: vendor/name") aus package.json bzw.
     * composer.json streichen. Die Lockfiles zieht erst npm install / composer update nach.
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

                $this->line("  - Paket {$manager}: {$package}");
                $touched[$manager] = true;

                if (! $dry) {
                    $indent = $manager === 'npm' ? 2 : 4;
                    $encoded = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                    File::put($path, preg_replace_callback('/^ +/m', fn ($m) => str_repeat(' ', strlen($m[0]) / 4 * $indent), $encoded)."\n");
                }
            }
        }

        if (isset($touched['npm'])) {
            $this->line('  Danach: npm install');
        }
        if (isset($touched['composer'])) {
            $this->line('  Danach: composer update');
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
