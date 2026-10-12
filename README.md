# immosite.ch

Starter for real-estate websites (from a landing page to a full website with apartment listings).
New projects are created from this repo via the Claude Code skill `immosite`.

- Conventions: [`CLAUDE.md`](CLAUDE.md)
- Modules: [`modules.md`](modules.md)
- Plan and background: [`docs/PLAN.md`](docs/PLAN.md)

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
