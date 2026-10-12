# immosite.ch

Starter für Immobilien-Websites (Landingpage bis vollständige Website mit Wohnungsangebot).
Neue Projekte entstehen aus diesem Repo über den Claude-Code-Skill `immosite`.

- Konventionen: [`CLAUDE.md`](CLAUDE.md)
- Module: [`modules.md`](modules.md)
- Plan und Hintergrund: [`docs/PLAN.md`](docs/PLAN.md)

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
herd link immosite.ch && herd secure immosite.ch   # → https://immosite.ch.test
```

## Tests

```bash
php artisan test
```
