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
- Format der Überschrift: `## <name> (optional)` oder `## <name> (optional, needs: a, b)`.

## Core (immer vorhanden)

Layout (`components/layout/*`), Menü, Überschriften, Buttons, Links, Icons, Seiten Home /
Impressum / Datenschutz, SEO (`config/seo.php`, `RobotsTag`, `/robots.txt`, `/sitemap.xml`),
Favicon/OG-Platzhalter, `config/site.php`, `module:remove`.

Für Formulare (von mehreren Modulen genutzt): `components/form/*` (input, textarea, checkbox,
error, honeypot, turnstile), `app/Support/Turnstile.php`, `pages/privacy/forms.blade.php`.

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
