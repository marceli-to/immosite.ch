# Sister project / reskin

"Like Adlerstrasse, but with these colours": the base is the **existing project**, not the
starter. Typical brief: an "Übersicht Website" sheet with only fonts and colours.

1. Copy the base project to `/Users/marceli.to/Jamon.digital/Webroot/<domain>` without `.git`,
   `vendor`, `node_modules`, `.env`, `storage/*.json`, uploads and `public/build`; new git repo
   and remote, `composer install`, `npm install`, new `.env` (`APP_URL`, keys), Herd link.
2. Tokens: new colours and fonts **only** in the `@theme` block of `resources/css/app.css`, the
   Typekit link in `config/site.php` → `fonts.stylesheet`, the mail colours in
   `resources/views/vendor/mail/html/themes/default.css`, `site.theme_color`. Map each colour of
   the sheet to the role the old colour had (primary, surface-alt, …) and show the mapping to
   Marcel first. If a class names a colour (`text-steel`), the base predates the semantic
   tokens: rename it to the role in the base project's pattern, don't copy the habit.
3. Swap content: logo (SVG in `components/icons/logo.blade.php`, PNG `public/img/mail-logo.png`),
   images, texts, favicon, OG image, `config/site.php`, apartment source URL, isometry
   (`isometry:build` with the new AI export), mail recipients, map coordinates.
4. Old projects without the starter structure (Statamic generation) stay as they are unless
   Marcel decides to rebuild on the starter; then it's [new-site.md](new-site.md) with the old
   site as the design.

Done when the site shows no trace of the base project: grep the old project name, address,
e-mail and domain across `resources`, `config`, `public` and `.env.example` and find none.
