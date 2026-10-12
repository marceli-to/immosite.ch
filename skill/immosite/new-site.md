# New site

## 1. Intake

Collect before touching code; ask for what's missing in one go:

- Project name, domain, client / marketer (name, address, contact person, e-mail, phone).
- Landing page or full site, and the modules (`modules.md` in the starter). Presets and the matching removal (removing a module also removes everything that needs it):
  - **landing with registration**: `php artisan module:remove contact-form faq building-spec downloads` (+ `weekly-export` / `map` / `gallery` / `disclaimer` if not wanted)
  - **landing with contact form**: `php artisan module:remove registration pages-full faq building-spec downloads`
  - **full**: start from all modules and remove what the brief doesn't show, e.g. `php artisan module:remove registration faq commercial isometry apartments-flatfox apartments-melon` for a static Mieterspiegel. Always run with `--dry-run` first and check the list.
- Apartment source: static (Mieterspiegel file), Flatfox URL, or Melon URL.
- Mail recipients: weekly export (+ BCC), contact form; the sender address (`MAIL_FROM_ADDRESS`, on a domain the mail server may send for).
- Design credits for footer and Impressum (default: stoz) and the hosting provider for the Datenschutz page.
- Map: coordinates of the building, extra markers.
- The design brief (PDF / Figma / screenshots) → [design-to-code.md](design-to-code.md) step 1. No brief: ask for colours and fonts, or keep the starter's.
- Images: originals from the client; images cut from the brief only as placeholders (open item).
- GitHub repo: public (default, like all marceli-to repos) or private.

## 2. Scaffold

```bash
cd /Users/marceli.to/Jamon.digital/Webroot
git clone /Users/marceli.to/Jamon.digital/Webroot/immosite.ch <domain>   # local starter, committed state
cd <domain>
rm -rf .git skill docs/PLAN.md && git init && git symbolic-ref HEAD refs/heads/main
# repo as marceli-to without switching the global gh account (--private if agreed)
GH_TOKEN=$(gh auth token --user marceli-to) gh repo create marceli-to/<domain> --public
git remote add origin git@github.com:marceli-to/<domain>.git
composer install && npm install
cp .env.example .env && php artisan key:generate && touch database/database.sqlite
php artisan module:remove <modules from the preset> --dry-run   # check, then without --dry-run
npm install && composer update --lock   # only if module:remove listed packages
php artisan migrate
herd link <domain> && herd secure <domain>                      # inside the project folder
sed -i '' "s#^APP_URL=.*#APP_URL=https://<domain>.test#" .env   # herd link writes http://
git add -A && git commit -m "Scaffold from immosite starter"
```

Rewrite `README.md` for the project (name, URL, setup, installed modules); in `CLAUDE.md` set the
first lines to the project and drop the `docs/PLAN.md` reference.

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
- `.env.example`: every key without secrets; `module:remove` printed which keys to delete.

**Full sites:** the home page still carries the landing sections (form, map). Move or drop them
as the brief shows. If the brief has a home page *and* a "Projekt" page, copy
`pages/home.blade.php` to `pages/project.blade.php`, add `Route::view('/projekt', …)->name('page.project')`
and its entry in `site.pages`.

## 4. Design and pages

Follow [design-to-code.md](design-to-code.md): tokens first (show them to Marcel before
building pages), then page by page, desktop to the design, tablet and mobile by
`docs/responsive-rules.md`.

## 5. Check and hand over

- Tests, build, `npm run screenshots -- <every page URL>`; compare 1024 with the design.
- `docs/open-items.md` lists what the client still has to deliver (from the brief).
- First commit and push; show Marcel the screenshots (mobile + tablet need his judgement).
