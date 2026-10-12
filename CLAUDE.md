# immosite – starter for real-estate websites

Laravel 13 · PHP 8.4 · Blade + Livewire 4 · Alpine (bundled with Livewire) · Tailwind 4 · Vite.
Local: https://immosite.ch.test (Herd). Background and roadmap: `docs/PLAN.md`.

This repo is the template new projects are created from. The base state is the
landing page; everything else is a module (`modules.md`).

## Commands

```bash
php artisan test     # feature tests
npm run build        # assets
npm run dev          # Vite with hot reload
```

After every change, `php artisan test` and `npm run build` must pass.

Remove modules / verify removal:

```bash
php artisan module:remove <name> [--dry-run]   # files, hooks, manifest entry
bin/check-module-removal <name> [<name> …]     # remove in a temp copy, then run tests + build
```

## Conventions

- **Routes:** German slugs (`/wohnen`, `/lage`, `/kontakt`, `/impressum`, `/datenschutz`, `/danke`), names `page.*`.
- **Page list:** `config/site.php` → `pages` feeds the main navigation, footer links and `/sitemap.xml`. New page = route in `routes/web.php` + one line in `pages`.
- **Views:** pages in `resources/views/pages/*.blade.php` (`@extends('app')`, `@section('meta_title')`, `@section('meta_description')`). Components under `resources/views/components/{layout,menu,headings,buttons,links,form,icons,media,gallery,sections,objects}`.
- **Language:** code, comments, docs and commit messages in English. Visitor-facing text (pages, form labels, validation messages, mails, legal pages) in German, Swiss spelling, no ß. Comments explain the *why*.
- **Colours are semantic tokens**, never named after the hue: `primary`, `primary-light`, `on-primary`, `surface`, `surface-alt`, `ink`, `muted`, `line`, later `status-free`, `status-reserved`, `status-rented` … Concrete values live only in the `@theme` block in `resources/css/app.css`. Wrong: `text-steel`, `bg-cocoa`. Only exception: mail colours in `resources/views/vendor/mail/html/themes/default.css` (mail clients don't support CSS variables); update them when reskinning.
- **Fonts** are tokens too (`--font-sans`, `--font-display`). The source (Typekit link) lives only in `config/site.php` → `fonts.stylesheet`.
- **Font sizes:** classic scale `text-xxs`, `text-xs`, `text-sm`, `text-lg`, `text-xl`, `text-2xl` … `text-8xl`, **without `base`**. The Tailwind defaults are removed (`--text-*: initial`), the px values live in `@theme`. Mobile first: `text-3xl md:text-5xl lg:text-6xl`. Line heights via `leading-*` in the component, not in the scale.
- **Spacing:** `--spacing` = 1px (from `xl` × `--scale`), so `px-18` = 18px. Take values straight from the design. No `--spacing-*` lists.
- **Responsive:** rules in `docs/responsive-rules.md`. Design = 1024px = `lg`, values there 1:1; `md` in-between values; mobile derived (titles ×0.6, section spacing ×0.5). From `xl`, `--scale` scales everything by 1.15, classes stay the same.
- **Header height** is the token `--header-height` (84 px, 96 px from `lg`, × `--scale`). Sticky elements and anchor offsets use it (`top-(--header-height)`), never a hard-coded number.
- **Burger menu:** automatic by number of nav entries (≤ 4 → below `md`, ≤ 6 → below `lg`, more → below `xl`), or `site.nav_breakpoint`.
- **Container:** `<x-layout.inner>` (`max-w-site`, padding 20 → 30 → 48px). Body text in `.prose-site` / `max-w-text`.
- **Config per topic** (`config/site.php`, `config/seo.php`, later `config/estate.php`, `config/melon.php` …). Secrets and URLs only via `.env`.
- **Don't import Alpine yourself**: Livewire ships it. Register plugins via `livewire:init` (see `resources/js/app.js`).
- **Generate machine formats (XML, CSV, JSON) in PHP**, not in Blade views (XMLWriter, fputcsv, json_encode). Blade only for HTML and mails; the editor flags XML in a `.blade.php` as broken HTML.
- No debug leftovers. Every API call has a timeout and error handling and keeps the last good data.

## Modules

Every optional feature is a module, described in `modules.md` (files, hooks, config/env, `needs:`).

- Every file of a module is listed in the manifest.
- Spots in shared code are marked: `// module:<name>` or `{{-- module:<name> --}}`. `grep -rn "module:<name>"` finds all hooks.
- Class imports (`use App\…;`) of a module's classes need no marker: `module:remove` deletes imports of classes whose files it removed (Pint turns fully qualified names into imports).
- A module touches shared code in as few places as possible, ideally one line.
- After removing a module, tests and build must still pass.

## SEO

- `ROBOTS_NOINDEX=true` (staging) → `/robots.txt` blocks everything and every response gets `X-Robots-Tag: noindex, nofollow`. Leave empty on live.
- Meta/OG tags in `<x-layout.head>`, values from `config/site.php` or the page sections.
