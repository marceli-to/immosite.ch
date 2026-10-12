# Module

Manifest aller optionalen Module. Der Skill `immosite` entfernt beim Aufsetzen eines Projekts
die nicht benötigten Module anhand dieser Liste und kopiert sie später bei Bedarf aus dem
Starter zurück.

Regeln:
- Jede Datei, die zu einem Modul gehört, ist aufgeführt.
- Hooks in geteiltem Code sind mit `// module:<name>` bzw. `{{-- module:<name> --}}` markiert.
- Entfernen eines Moduls entfernt auch die Module, die es brauchen (`needs:`). Hinzufügen prüft `needs:` zuerst.
- Nach dem Entfernen müssen `php artisan test` und `npm run build` grün sein.

## Core (immer vorhanden)

Layout (`components/layout/*`), Menü, Überschriften, Buttons, Links, Icons, Seiten Home /
Impressum / Datenschutz, SEO (`config/seo.php`, `RobotsTag`, `/robots.txt`, `/sitemap.xml`),
Favicon/OG-Platzhalter, `config/site.php`.

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
