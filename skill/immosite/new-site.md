# New site

## 1. Intake

Collect before touching code; ask for what's missing in one go:

- Project name, domain, client / marketer (name, address, contact person, e-mail, phone).
- Landing page or full site, and the modules (`modules.md` in the starter). Presets:
  - **landing**: core + `registration` or `contact-form` + `map` + `gallery` (+ `disclaimer`)
  - **full**: landing + `pages-full` + `apartments` + one driver (+ `commercial`, `weekly-export`, `apply`, `isometry`, `faq`, `building-spec`, `downloads`, `melon-lead-hook` as the brief asks)
- Apartment source: static (Mieterspiegel file), Flatfox URL, or Melon URL.
- Mail recipients: weekly export (+ BCC), contact form.
- Map: coordinates of the building, extra markers.
- The design brief (PDF / Figma / screenshots) → [design-to-code.md](design-to-code.md) step 1.
- GitHub repo: public (default, like all marceli-to repos) or private.

## 2. Scaffold

```bash
cd /Users/marceli.to/Jamon.digital/Webroot
git clone git@github.com:marceli-to/immosite.ch.git <domain>
cd <domain>
rm -rf .git skill docs/PLAN.md && git init && git symbolic-ref HEAD refs/heads/main
# repo as marceli-to without switching the global gh account (--private if agreed)
GH_TOKEN=$(gh auth token --user marceli-to) gh repo create marceli-to/<domain> --public
git remote add origin git@github.com:marceli-to/<domain>.git
composer install && npm install
cp .env.example .env && php artisan key:generate && touch database/database.sqlite
php artisan module:remove <every module the project doesn't use>
npm install && composer update --lock   # only if module:remove listed packages
php artisan migrate
herd link <domain> && herd secure <domain>
```

Done when: `php artisan test` and `npm run build` pass and `https://<domain>.test` loads.
`modules.md` in the project now lists exactly the installed modules.

## 3. Configure

- `.env`: `APP_NAME`, `APP_URL=https://<domain>.test`, `ROBOTS_NOINDEX=true`, mail, driver
  keys (`APARTMENTS_DRIVER`, `FLATFOX_API_URL` / `MELON_API_URL`), `MAPBOX_TOKEN` (remind Marcel
  to allow `https://<domain>.test/`, staging and live on the token), export recipients.
  `.env.example` gets every key, without secrets.
- `config/site.php`: name, title, description, marketer, credits, hosting, pages (titles).
- Module configs (`config/registration.php`, `apartments.php`, `map.php`, `location.php`, …): fields,
  interests, columns, filters, floors, houses, coordinates, distances.
- `CLAUDE.md`: first lines → project name and URL, keep the conventions.

## 4. Design and pages

Follow [design-to-code.md](design-to-code.md): tokens first (show them to Marcel before
building pages), then page by page, desktop to the design, tablet and mobile by
`docs/responsive-rules.md`.

## 5. Check and hand over

- Tests, build, `npm run screenshots -- <every page URL>`; compare 1024 with the design.
- `docs/open-items.md` lists what the client still has to deliver (from the brief).
- First commit and push; show Marcel the screenshots (mobile + tablet need his judgement).
