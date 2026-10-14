# Modules

Manifest of all optional modules. When setting up a project, the `immosite` skill removes
the modules that aren't needed using this list, and later copies them back from the
starter if required. Inside a project, this file describes what is installed there.

```bash
php artisan module:remove gallery map        # removes modules (+ all modules that need them)
php artisan module:remove gallery --dry-run  # only show what would happen
```

Rules:
- Every file that belongs to a module is listed under `Files:`.
- Hooks in shared code are **exactly one line** and marked with `// module:<name>` or `{{-- module:<name> --}}`. If a line belongs to several modules (`module:a,b`), it stays as long as one of them is installed.
- `Hooks:` describes the lines for humans; `module:remove` finds them via the marker.
- Removing a module also removes the modules that need it (`needs:`). Adding checks `needs:` first.
- After removal, `php artisan test` and `npm run build` must pass.
- `module:remove` deletes the `Packages:` (`npm: name`, `composer: vendor/name`) from package.json / composer.json.
- Heading format: `## <name> (optional)` or `## <name> (optional, needs: a, b)`.

## Core (always present)

Layout (`components/layout/*`), menu, headings, buttons, links, icons, pages Home /
Impressum / Datenschutz, SEO (`config/seo.php`, `RobotsTag`, `/robots.txt`, `/sitemap.xml`),
favicon/OG placeholders, `config/site.php`, `module:remove`, `npm run screenshots`, pattern library `/muster` (local only, one file per pattern in `resources/views/patterns`, modules bring their own), `x-sections.text`, `x-sections.split` (text + image, `bleed` to the window edge), `x-sections.hero` (image/slider with title, optional `x-sections.stats` key figures), `x-sections.columns`,
`x-accordion.group` / `x-accordion.item` (Alpine collapse), `x-media.picture` (image with AVIF/WebP/mobile variants,
dimensions read from the file), placeholder images `public/img/placeholder-{1,2,3}.{jpg,webp}`.

Mails: custom header/footer and token colours in `resources/views/vendor/mail/html`.

For forms (used by several modules): `components/form/*` (input, textarea, checkbox,
error, honeypot, turnstile, contact-fields), `app/Livewire/Concerns/ContactFields.php` (shared fields
and rules), `app/Support/Turnstile.php`, `pages/privacy/forms.blade.php`.

---

## disclaimer (optional)
Disclaimer page (liability disclaimer, copyright).
Files:
- resources/views/pages/disclaimer.blade.php
Hooks:
- routes/web.php → Route::view('/disclaimer', …)->name('page.disclaimer')
- config/site.php → pages: entry `page.disclaimer`
Config/env:
- –

---

## registration (optional)
Registration form (Livewire) on the home page: interest selection, contact details, privacy
checkbox, honeypot + optional Turnstile, confirmation mail to the sender, optional mail to the
marketer per registration (`REGISTRATIONS_NOTIFY_EMAIL`). Fields per project in
`config/registration.php` (required / optional / hidden).
Files:
- app/Livewire/RegistrationForm.php
- app/Models/Registration.php
- app/Mail/RegistrationConfirmation.php
- app/Mail/RegistrationReceived.php
- config/registration.php
- database/migrations/2026_10_12_000001_create_registrations_table.php
- resources/views/livewire/registration-form.blade.php
- resources/views/mail/registration-confirmation.blade.php
- resources/views/mail/registration-received.blade.php
- resources/views/sections/registration.blade.php
- tests/Feature/RegistrationFormTest.php
Hooks:
- resources/views/pages/home.blade.php → @include('sections.registration')
- resources/views/pages/privacy.blade.php → @include('pages.privacy.forms') (shared with contact-form)
Config/env:
- TURNSTILE_SITE_KEY, TURNSTILE_SECRET_KEY, MAIL_FROM_ADDRESS, REGISTRATIONS_NOTIFY_EMAIL

---

## weekly-export (optional, needs: registration)
Weekly mail with the new registrations (list + CSV) to the marketer, including empty
weeks. Marks them as exported only after a successful send. Weekday and time via `.env`.
Files:
- app/Console/Commands/ExportWeeklyRegistrations.php
- app/Notifications/WeeklyRegistrationsExport.php
- config/registration-export.php
- resources/views/mail/registrations-export.blade.php
- tests/Feature/WeeklyExportTest.php
Hooks:
- routes/console.php → Schedule::command('registrations:export-weekly')
Config/env:
- REGISTRATIONS_EXPORT_EMAIL, REGISTRATIONS_EXPORT_BCC, REGISTRATIONS_EXPORT_DAY, REGISTRATIONS_EXPORT_TIME

---

## contact-form (optional)
Contact form (Livewire) without a database: mail to the marketer (Reply-To = sender),
confirmation to the sender, then a thank-you page (noindex). If the mail to the agency
fails, the sender sees an error message. Fields in `config/contact.php`.
Landing page: instead of or in addition to `registration` on the home page.
Files:
- app/Livewire/ContactForm.php
- app/Mail/ContactInquiry.php
- app/Mail/ContactConfirmation.php
- config/contact.php
- resources/views/livewire/contact-form.blade.php
- resources/views/mail/contact-inquiry.blade.php
- resources/views/mail/contact-confirmation.blade.php
- resources/views/sections/contact.blade.php
- resources/views/pages/thanks.blade.php
- tests/Feature/ContactFormTest.php
Hooks:
- routes/web.php → Route::view('/danke', …)->name('page.thanks')
- resources/views/pages/home.blade.php → @include('sections.contact')
- resources/views/pages/privacy.blade.php → @include('pages.privacy.forms') (shared with registration)
Config/env:
- CONTACT_RECIPIENTS, TURNSTILE_SITE_KEY, TURNSTILE_SECRET_KEY, MAIL_FROM_ADDRESS

---

## map (optional)
Full-width Mapbox map, markers in the `primary` colour, two-finger panning on touch
devices. Mapbox is only loaded on pages with a map. Location and markers in
`config/map.php`.
Files:
- resources/views/patterns/51-map.blade.php
- config/map.php
- resources/views/components/map.blade.php
- resources/js/modules/map.js
- resources/views/pages/privacy/mapbox.blade.php
- tests/Feature/MapTest.php
Hooks:
- resources/js/app.js → import './modules/map.js'
- resources/views/pages/home.blade.php → <x-map />
- resources/views/pages/privacy.blade.php → @include('pages.privacy.mapbox')
Packages:
- npm: mapbox-gl
Config/env:
- MAPBOX_TOKEN, MAPBOX_STYLE

---

## gallery (optional)
Image slider (Swiper) for hero and carousels: swipe, arrows, dots; autoplay stops on
interaction and with "reduce motion". Image grid (`x-gallery.grid`) with a full-screen
lightbox (native `<dialog>`, keyboard, groups per page). Swiper is only loaded when needed. Aspect ratio
4:3 / 16:9 / 2:1 (docs/responsive-rules.md §8). Images via `x-media.picture` (core).
Files:
- resources/views/patterns/50-gallery.blade.php
- resources/views/patterns/55-gallery-grid.blade.php
- resources/views/components/gallery/slider.blade.php
- resources/views/components/gallery/grid.blade.php
- resources/js/modules/gallery.js
- resources/js/modules/lightbox.js
- resources/css/gallery.css
- tests/Feature/GalleryTest.php
Hooks:
- resources/js/app.js → import './modules/gallery.js'
- resources/css/app.css → @import './gallery.css'
- resources/views/pages/home.blade.php → <x-gallery.slider … /> (Hero)
Packages:
- npm: swiper
Config/env:
- –

---

## pages-full (optional, needs: contact-form)
Turns the landing page into a website with navigation: pages Lage (text, distance table,
Google Maps button, map if `map` is installed) and Kontakt (intro + contact form).
Distances and Maps link in `config/location.php`.
Files:
- resources/views/patterns/52-distances.blade.php
- config/location.php
- resources/views/pages/location.blade.php
- resources/views/pages/contact.blade.php
- resources/views/components/sections/distances.blade.php
- tests/Feature/FullPagesTest.php
Hooks:
- routes/web.php → Route::view('/lage', …), Route::view('/kontakt', …)
- config/site.php → pages: `page.location`, `page.contact` (Navigation)
Config/env:
- –

---

## apartments (optional, needs: pages-full)
Apartment listing on /wohnen: filters (status, rooms, floor, house) and table(s), columns,
filters, grouping by house, floor labels, sort order, footnote and a static parking/extras table
(`extras`) from `config/apartments.php`.
All sources return the same `App\Apartments\Apartment`; the driver comes from
`APARTMENTS_DRIVER`. Mobile (`apartments.mobile`): 'list' = a few columns per row, the rest
opens below it and marks the apartment in the isometry; 'scroll' = table scrolls sideways. Install at least one
driver module.
Files:
- resources/views/patterns/54-apartments.blade.php
- app/Apartments/Apartment.php
- app/Apartments/ApartmentRepository.php
- app/Apartments/ApiSource.php
- app/Apartments/Format.php
- app/Apartments/Source.php
- app/Apartments/Status.php
- app/Console/Commands/FetchApartments.php
- app/Http/Controllers/ApartmentController.php
- config/apartments.php
- resources/views/pages/apartments.blade.php
- resources/views/components/apartments
- resources/js/modules/apartment-filter.js
- tests/Feature/ApartmentsTest.php
- tests/Support/FakeApartmentSource.php
Hooks:
- routes/web.php → use ApartmentController; Route::get('/wohnen', …)
- config/site.php → pages: `page.apartments` (navigation)
- resources/js/app.js → import './modules/apartment-filter.js'
- resources/css/app.css → --color-status-free / -reserved / -rented
Config/env:
- APARTMENTS_DRIVER

---

## apartments-static (optional, needs: apartments)
Apartments maintained by hand in `config/apartments-static.php`, in Mieterspiegel order.
Files:
- app/Apartments/Sources/StaticSource.php
- config/apartments-static.php
- tests/Feature/StaticSourceTest.php
Hooks:
- config/apartments.php → drivers: 'static'
Config/env:
- APARTMENTS_DRIVER=static

---

## apartments-flatfox (optional, needs: apartments)
Flatfox public listing API: follows `next` pages, newer duplicate (higher pk) wins, pre/act/dis
= published (reserved flag), arc/rem = rented, Flatfox apply link per listing, collective
listings (house 99, `flatfox.exclude_houses` / `exclude_refs`) dropped. Fetched every
15 minutes by `apartments:fetch` into storage/app/apartments.json; a failed or empty fetch
keeps the last data.
Files:
- app/Apartments/Sources/FlatfoxSource.php
- config/flatfox.php
- tests/Feature/FlatfoxSourceTest.php
- tests/fixtures/flatfox-page-1.json
- tests/fixtures/flatfox-page-2.json
Hooks:
- config/apartments.php → drivers: 'flatfox'
- routes/console.php → Schedule::command('apartments:fetch') (shared with apartments-melon)
Config/env:
- APARTMENTS_DRIVER=flatfox, FLATFOX_API_URL

---

## apartments-melon (optional, needs: apartments)
Melon / emonitor v2 feed (flat array). `MELON_MOCK=true` serves database/data/melon-mock.json.
Same fetch/cache behaviour as Flatfox.
Files:
- app/Apartments/Sources/MelonSource.php
- config/melon.php
- database/data/melon-mock.json
- tests/Feature/MelonSourceTest.php
Hooks:
- config/apartments.php → drivers: 'melon'
- routes/console.php → Schedule::command('apartments:fetch') (shared with apartments-flatfox)
Config/env:
- APARTMENTS_DRIVER=melon, MELON_API_URL, MELON_MOCK

---

## faq (optional)
FAQ page /faq with an accordion (one open at a time, animated). Questions and answers in
`config/faq.php`.
Files:
- config/faq.php
- resources/views/pages/faq.blade.php
- tests/Feature/FaqTest.php
Hooks:
- routes/web.php → Route::view('/faq', …)
- config/site.php → pages: `page.faq` (navigation)
Config/env:
- –

---

## building-spec (optional)
Page /ausbau with the Kurzbaubeschrieb: sections and lines from `config/building-spec.php`,
shown as a list or an accordion.
Files:
- config/building-spec.php
- resources/views/pages/building-spec.blade.php
- tests/Feature/BuildingSpecTest.php
Hooks:
- routes/web.php → Route::view('/ausbau', …)
- config/site.php → pages: `page.building-spec` (navigation)
Config/env:
- –

---

## downloads (optional)
PDF buttons per group (`config/downloads.php`), files in public/downloads. Missing files show a
disabled "PDF folgt" button. Used on /ausbau and /wohnen.
Files:
- resources/views/patterns/53-downloads.blade.php
- config/downloads.php
- resources/views/components/downloads
- public/downloads/.gitkeep
- tests/Feature/DownloadsTest.php
Hooks:
- resources/views/pages/building-spec.blade.php → <x-downloads.buttons group="building-spec" />
- resources/views/pages/apartments.blade.php → <x-downloads.buttons group="apartments" />
Config/env:
- –

---

## apply (optional, needs: apartments, contact-form)
"Bewerben" button per free/reserved apartment: the source's application link (Flatfox/Melon)
or our form on /bewerben?wohnung=REF (contact form with the apartment on top of the message;
with `apply.multiple` the visitor can add more apartments from the list).
Mode and fields in `config/apply.php`.
Files:
- app/Livewire/ApplicationForm.php
- config/apply.php
- resources/views/livewire/application-form.blade.php
- resources/views/components/apartments/apply.blade.php
- resources/views/pages/apply.blade.php
- tests/Feature/ApplyTest.php
Hooks:
- routes/web.php → Route::view('/bewerben', …)
- config/apartments.php → columns: 'apply'
- resources/views/components/apartments/table.blade.php → @case('apply')
Config/env:
- –

---

## melon-lead-hook (optional, needs: contact-form, apartments-melon)
Sends contact and application form submissions to Melon as a lead (JSON POST, Basic Auth,
PLZ/Ort split). Best effort: failures are logged, the visitor never sees them.
Files:
- app/Support/SendLeadToMelon.php
- config/melon-lead.php
- tests/Feature/MelonLeadHookTest.php
Hooks:
- app/Livewire/ContactForm.php → app(SendLeadToMelon::class)->send($data)
Config/env:
- MELON_HOOK_ENABLED, MELON_HOOK_URL, MELON_HOOK_USERNAME, MELON_HOOK_PASSWORD, MELON_HOOK_TIMEOUT

---

## commercial (optional, needs: apartments)
Page /gewerbe with the commercial units in the apartment table (own columns). Source in
`config/commercial.php`: static list, the apartments driver (type `commercial`), or the
database (`commercial-admin`).
Files:
- app/Apartments/Sources/StaticCommercialSource.php
- app/Apartments/Sources/ApartmentsCommercialSource.php
- app/Http/Controllers/CommercialController.php
- config/commercial.php
- resources/views/pages/commercial.blade.php
- tests/Feature/CommercialTest.php
Hooks:
- routes/web.php → use CommercialController; Route::get('/gewerbe', …)
- config/site.php → pages: `page.commercial` (navigation)
Config/env:
- COMMERCIAL_SOURCE

---

## commercial-admin (optional, needs: commercial)
/admin for the marketer: login (rate-limited), edit status, net rent and charges of the
commercial units. Objects are seeded from `config/commercial.php` with `commercial:seed`;
logins are created with `user:create` (no default password in the repo).
Set `COMMERCIAL_SOURCE=database`.
Files:
- app/Apartments/Sources/DatabaseCommercialSource.php
- app/Console/Commands/CreateUser.php
- app/Console/Commands/SeedCommercialObjects.php
- app/Http/Controllers/Admin
- app/Models/CommercialObject.php
- database/migrations/2026_10_12_000002_create_commercial_objects_table.php
- resources/views/admin
- routes/admin.php
- tests/Feature/CommercialAdminTest.php
Hooks:
- routes/web.php → require __DIR__.'/admin.php'
- config/commercial.php → sources: 'database'
Config/env:
- COMMERCIAL_SOURCE=database

---

## isometry (optional, needs: apartments)
Interactive building drawing next to the apartment table: status colours from the table,
hover/focus on a row lifts the floors above and marks the apartment, hover/tap on the
drawing marks the row (tap scrolls to it), filtered-out apartments are dimmed. Mobile:
sticky above the table, max 45vh; from lg: right column. Built from the designer's
Illustrator SVG with `php artisan isometry:build` (see resources/isometry/README.md).
Files:
- app/Console/Commands/BuildIsometry.php
- config/isometry.php
- resources/isometry
- resources/views/components/isometry.blade.php
- resources/views/isometry
- resources/css/isometry.css
- resources/js/modules/isometry.js
- tests/Feature/IsometryTest.php
Hooks:
- resources/views/pages/apartments.blade.php → @include('isometry.aside')
- resources/js/app.js → import './modules/isometry.js'
- resources/css/app.css → @import './isometry.css', --color-iso-* tokens
Config/env:
- –

---

## scroll-reveal (optional)
Fade-in on scroll without a library (IntersectionObserver): `data-reveal` elements and the
children of `data-reveal-children` (staggered). Starter sections already carry the attributes.
Off with "reduce motion"; without JS, content stays visible (fallback after 2.5 s).
Files:
- resources/js/modules/scroll-reveal.js
- resources/css/scroll-reveal.css
- resources/views/scroll-reveal
Hooks:
- resources/views/components/layout/head.blade.php → @include('scroll-reveal.head')
- resources/js/app.js → import './modules/scroll-reveal.js'
- resources/css/app.css → @import './scroll-reveal.css'
Config/env:
- –
