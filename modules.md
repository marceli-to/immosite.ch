# Module

Manifest aller optionalen Module. Der Skill `immosite` entfernt beim Aufsetzen eines Projekts
die nicht benötigten Module anhand dieser Liste und kopiert sie später bei Bedarf aus dem
Starter zurück. In einem Projekt beschreibt diese Datei, was dort installiert ist.

```bash
php artisan module:remove gallery map        # entfernt Module (+ alle, die sie brauchen)
php artisan module:remove gallery --dry-run  # nur anzeigen
```

Regeln:
- Jede Datei, die zu einem Modul gehört, steht unter `Files:`.
- Hooks in geteiltem Code sind **genau eine Zeile** und mit `// module:<name>` bzw. `{{-- module:<name> --}}` markiert. Gehört eine Zeile mehreren Modulen (`module:a,b`), bleibt sie, solange eines davon installiert ist.
- `Hooks:` beschreibt die Zeilen für Menschen; `module:remove` findet sie über die Markierung.
- Entfernen eines Moduls entfernt auch die Module, die es brauchen (`needs:`). Hinzufügen prüft `needs:` zuerst.
- Nach dem Entfernen müssen `php artisan test` und `npm run build` grün sein.
- `Packages:` (`npm: name`, `composer: vendor/name`) streicht `module:remove` aus package.json / composer.json.
- Format der Überschrift: `## <name> (optional)` oder `## <name> (optional, needs: a, b)`.

## Core (immer vorhanden)

Layout (`components/layout/*`), Menü, Überschriften, Buttons, Links, Icons, Seiten Home /
Impressum / Datenschutz, SEO (`config/seo.php`, `RobotsTag`, `/robots.txt`, `/sitemap.xml`),
Favicon/OG-Platzhalter, `config/site.php`, `module:remove`, `x-media.picture` (Bild mit AVIF/WebP/Mobile-Varianten,
Abmessungen aus der Datei).

Mails: eigene Kopf-/Fusszeile und Token-Farben in `resources/views/vendor/mail/html`.

Für Formulare (von mehreren Modulen genutzt): `components/form/*` (input, textarea, checkbox,
error, honeypot, turnstile, contact-fields), `app/Livewire/Concerns/ContactFields.php` (gemeinsame Felder
und Regeln), `app/Support/Turnstile.php`, `pages/privacy/forms.blade.php`.

---

## disclaimer (optional)
Disclaimer-Seite (Haftungsausschluss, Urheberrecht).
Files:
- resources/views/pages/disclaimer.blade.php
Hooks:
- routes/web.php → Route::view('/disclaimer', …)->name('page.disclaimer')
- config/site.php → pages: Eintrag `page.disclaimer`
Config/env:
- –

---

## registration (optional)
Anmeldeformular (Livewire) auf der Startseite: Interessen-Auswahl, Kontaktdaten, Datenschutz-
Checkbox, Honeypot + optional Turnstile, Bestätigungsmail an den Absender. Felder pro Projekt in
`config/registration.php` (Pflicht / optional / ausgeblendet).
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
- resources/views/pages/privacy.blade.php → @include('pages.privacy.forms') (geteilt mit contact-form)
Config/env:
- TURNSTILE_SITE_KEY, TURNSTILE_SECRET_KEY, MAIL_FROM_ADDRESS

---

## weekly-export (optional, needs: registration)
Wöchentliche Mail mit den neuen Anmeldungen (Liste + CSV) an die Vermarktung, inkl. leerer
Wochen. Markiert erst nach erfolgreichem Versand. Wochentag und Zeit per `.env`.
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
Kontaktformular (Livewire) ohne Datenbank: Mail an die Vermarktung (Reply-To = Absender),
Bestätigung an den Absender, danach Danke-Seite (noindex). Scheitert die Mail an die
Vermarktung, sieht der Absender eine Fehlermeldung. Felder in `config/contact.php`.
Landingpage: statt oder zusätzlich zu `registration` auf der Startseite.
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
- resources/views/pages/privacy.blade.php → @include('pages.privacy.forms') (geteilt mit registration)
Config/env:
- CONTACT_RECIPIENTS, TURNSTILE_SITE_KEY, TURNSTILE_SECRET_KEY, MAIL_FROM_ADDRESS

---

## map (optional)
Mapbox-Karte über die volle Breite, Marker in der Farbe `primary`, zwei Finger zum Bewegen
auf Touch-Geräten. Mapbox wird nur auf Seiten mit Karte geladen. Lage und Marker in
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
Bilder-Slider (Swiper) für Hero und Karussells: Wischen, Pfeile, Punkte, Autoplay stoppt bei
Interaktion und bei «Bewegung reduzieren». Swiper wird nur bei Bedarf geladen. Seitenverhältnis
4:3 / 16:9 / 2:1 (docs/responsive-rules.md §8). Bilder über `x-media.picture` (Core).
Files:
- resources/views/components/gallery/slider.blade.php
- resources/js/modules/gallery.js
- resources/css/gallery.css
- public/img/placeholder-1.jpg
- public/img/placeholder-1.webp
- public/img/placeholder-2.jpg
- public/img/placeholder-2.webp
- public/img/placeholder-3.jpg
- public/img/placeholder-3.webp
- tests/Feature/GalleryTest.php
Hooks:
- resources/js/app.js → import './modules/gallery.js'
- resources/css/app.css → @import './gallery.css'
- resources/views/pages/home.blade.php → <x-gallery.slider … /> (Hero)
Packages:
- npm: swiper
Config/env:
- –
