# immosite – Starter für Immobilien-Websites

Laravel 13 · PHP 8.4 · Blade + Livewire 4 · Alpine (aus Livewire) · Tailwind 4 · Vite.
Lokal: https://immosite.ch.test (Herd). Hintergrund und Fahrplan: `docs/PLAN.md`.

Dieses Repo ist die Vorlage, aus der neue Projekte entstehen. Der Grundzustand ist die
Landingpage; alles Weitere sind Module (`modules.md`).

## Befehle

```bash
php artisan test     # Feature-Tests
npm run build        # Assets
npm run dev          # Vite mit Hot Reload
```

Nach jeder Änderung müssen `php artisan test` und `npm run build` grün sein.

Module entfernen / Entfernen prüfen:

```bash
php artisan module:remove <name> [--dry-run]   # Dateien, Hooks, Manifest-Eintrag
bin/check-module-removal <name> [<name> …]     # in Temp-Kopie entfernen, dann Tests + Build
```

## Konventionen

- **Routen:** deutsche Slugs (`/wohnen`, `/lage`, `/kontakt`, `/impressum`, `/datenschutz`, `/danke`), Namen `page.*`.
- **Seitenliste:** `config/site.php` → `pages` speist Hauptnavigation, Footer-Links und `/sitemap.xml`. Neue Seite = Route in `routes/web.php` + eine Zeile in `pages`.
- **Views:** Seiten in `resources/views/pages/*.blade.php` (`@extends('app')`, `@section('meta_title')`, `@section('meta_description')`). Komponenten unter `resources/views/components/{layout,menu,headings,buttons,links,form,icons,media,gallery,sections,objects}`.
- **Sprache:** UI-Texte und Code-Kommentare deutsch, Schweizer Rechtschreibung, kein ß. In Kommentaren ae/oe/ue statt Umlaute. Kommentare erklären das *Warum*.
- **Farben sind semantische Tokens**, nie nach dem Farbton benannt: `primary`, `primary-light`, `on-primary`, `surface`, `surface-alt`, `ink`, `muted`, `line`, später `status-free`, `status-reserved`, `status-rented` … Konkrete Werte stehen nur im `@theme`-Block in `resources/css/app.css`. Falsch: `text-steel`, `bg-cocoa`.
- **Schriften** sind ebenfalls Tokens (`--font-sans`, `--font-display`). Die Quelle (Typekit-Link) steht nur in `config/site.php` → `fonts.stylesheet`.
- **Schriftgrössen:** klassische Skala `text-xxs`, `text-xs`, `text-sm`, `text-lg`, `text-xl`, `text-2xl` … `text-8xl`, **ohne `base`**. Die Tailwind-Defaults sind gelöscht (`--text-*: initial`), die px-Werte stehen im `@theme`. Mobile zuerst: `text-3xl md:text-5xl lg:text-6xl`. Zeilenhöhen per `leading-*` in der Komponente, nicht in der Skala.
- **Abstände:** `--spacing` = 1px (ab `xl` × `--scale`), also `px-18` = 18px. Werte direkt aus dem Design übernehmen. Keine `--spacing-*`-Listen.
- **Responsive:** Regeln in `docs/responsive-rules.md`. Entwurf = 1024px = `lg`, Werte dort 1:1; `md` Zwischenwerte; Mobile abgeleitet (Titel ×0.6, Abschnittsabstände ×0.5). Ab `xl` skaliert `--scale` alles um 1.15, Klassen bleiben gleich.
- **Container:** `<x-layout.inner>` (`max-w-site`, Padding 20 → 30 → 48px). Fliesstext in `.prose-site` / `max-w-text`.
- **Config pro Thema** (`config/site.php`, `config/seo.php`, später `config/estate.php`, `config/melon.php` …). Secrets und URLs nur über `.env`.
- **Alpine nicht selbst importieren**: Livewire bringt es mit. Plugins über `livewire:init` registrieren (siehe `resources/js/app.js`).
- Keine Debug-Reste. Jeder API-Abruf hat Timeout und Fehlerbehandlung und behält die letzten guten Daten.

## Module

Jedes optionale Feature ist ein Modul, beschrieben in `modules.md` (Dateien, Hooks, Config/Env, `needs:`).

- Jede Datei eines Moduls steht im Manifest.
- Stellen in geteiltem Code sind markiert: `// module:<name>` bzw. `{{-- module:<name> --}}`. `grep -rn "module:<name>"` findet alle Hooks.
- Ein Modul berührt geteilten Code an möglichst wenigen Stellen, idealerweise einer Zeile.
- Nach dem Entfernen eines Moduls müssen Tests und Build weiterhin grün sein.

## SEO

- `ROBOTS_NOINDEX=true` (Staging) → `/robots.txt` sperrt alles und jede Response bekommt `X-Robots-Tag: noindex, nofollow`. Auf Live leer lassen.
- Meta-/OG-Tags im `<x-layout.head>`, Werte aus `config/site.php` bzw. den Seiten-Sections.
