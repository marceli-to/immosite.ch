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
favicon/OG placeholders, `config/site.php`, `module:remove`, `x-sections.text`, `x-sections.split` (text + image),
`x-media.picture` (image with AVIF/WebP/mobile variants,
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
checkbox, honeypot + optional Turnstile, confirmation mail to the sender. Fields per project in
`config/registration.php` (required / optional / hidden).
Files:
- app/Livewire/RegistrationForm.php
- app/Models/Registration.php
- app/Mail/RegistrationConfirmation.php
- config/registration.php
- database/migrations/2026_10_12_000001_create_registrations_table.php
- resources/views/livewire/registration-form.blade.php
- resources/views/mail/registration-confirmation.blade.php
- resources/views/sections/registration.blade.php
- tests/Feature/RegistrationFormTest.php
Hooks:
- resources/views/pages/home.blade.php → @include('sections.registration')
- resources/views/pages/privacy.blade.php → @include('pages.privacy.forms') (shared with contact-form)
Config/env:
- TURNSTILE_SITE_KEY, TURNSTILE_SECRET_KEY, MAIL_FROM_ADDRESS

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
interaction and with "reduce motion". Swiper is only loaded when needed. Aspect ratio
4:3 / 16:9 / 2:1 (docs/responsive-rules.md §8). Images via `x-media.picture` (core).
Files:
- resources/views/components/gallery/slider.blade.php
- resources/js/modules/gallery.js
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
