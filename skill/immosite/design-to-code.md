# Design to code

Desktop design in, desktop + tablet + mobile out. The rules for deriving tablet and mobile
are in the project's `docs/responsive-rules.md`; read them before step 3. Example briefs
(stoz): `/Users/marceli.to/Jamon.digital/Webroot/immosite.ch/docs/briefs/` (not in git).

## Sources

| Source | What to take | Note |
|---|---|---|
| stoz "Website-Beschreibung" PDF | One desktop mockup per page, 1024 pt = 1024 px wide = `lg`. The text layer has exact font sizes. | **Magenta text is instructions to us, not content**: fonts (+ Typekit link), colours (hex + role), link targets, "Slider: mit drei Bildern", "PLATZHALTER", "PDF folgt", "Inhalte aus … hinterlegen", isometry colours. |
| stoz "Übersicht Website" | Fonts + colours only | A sister project: [sister-project.md](sister-project.md). |
| Figma (usually desktop only) | Exact values via Dev Mode / Figma MCP | Width of the frame = the design width. |
| Screenshots | Layout, hierarchy, approximate sizes | Ask for the width they were taken at. |

## 1. Read the brief

Tool (macOS PDFKit, nothing to install; PDFKit logs to stderr):

```bash
S=/Users/marceli.to/Jamon.digital/Webroot/immosite.ch/skill/immosite/scripts/brief.swift
swift $S text <brief.pdf> --magenta 2>/dev/null   # the designer's notes, with position
swift $S text <brief.pdf> 2>/dev/null             # every text run: font, size, colour, x/y
swift $S render <brief.pdf> /tmp/brief 2>/dev/null # pages as PNG at 1024 px, to compare with screenshots
```

Produce three lists, all in one message to Marcel:

1. **Tokens**: every colour with its role (→ `primary`, `primary-light`, `on-primary`, `surface`,
   `surface-alt`, `ink`, `muted`, `line`, `status-*`, `iso-*`), fonts + weights + source, the
   font sizes used per role (h1, h2, h3, body, small, nav, button), container width and side
   margin at the design width.
2. **Pages and sections**: per page, its sections mapped to starter parts (table below), and
   what is new.
3. **Open items** → `docs/open-items.md`: every placeholder image, "PDF folgt", "Pläne folgen",
   content to take from other files (Kurzbaubeschrieb, FAQ, Mieterspiegel), missing logo/SVG,
   missing Typekit access, unclear link targets.

Done when every magenta annotation of the brief (`--magenta` output) is in one of the three lists.

**No brief** (Marcel gives colours/fonts directly or none): keep the starter's type scale and
spacing, set the given colours and fonts, write short placeholder copy marked "Platzhalter",
and list everything still needed in `docs/open-items.md`.

## 2. Tokens (show to Marcel before building pages)

- Colours into the `@theme` block of `resources/css/app.css`, by role, never by colour name.
  Roles beyond the basics: `header` / `on-header` (header bar, mobile menu), `field` (form field
  borders, independent of `line`), `status-*`, `iso-*`. Mail colours into
  `resources/views/vendor/mail/html/themes/default.css`; `site.theme_color`.
- Fonts: Adobe Fonts → Typekit URL in `config/site.php` → `fonts.stylesheet`. Google or
  self-hosted fonts → `npm install @fontsource/<font>` and `@import` it in `app.css` (or files in
  `public/fonts` with `@font-face`), `fonts.stylesheet` stays null; the Datenschutz section on
  Adobe Fonts then disappears by itself. Set `--font-sans` / `--font-display`.
- Font sizes: set the px values of the scale steps (`--text-xs` … `--text-8xl`, no `base`) so the
  design sizes land on steps; mobile sizes follow `docs/responsive-rules.md` §2. Body text is set
  in `app.css` (`@layer base`, `body`): change those classes and the line height to the design's
  body size, and the component defaults (buttons, h3) where the design differs.
- Container: `--container-site` = design width incl. margins, side padding at `lg` = design margin.

## 3. Build page by page

| Brief section | Starter part |
|---|---|
| Header, nav, active state | `components/layout/header`, `menu/*` (burger breakpoint: `site.nav_breakpoint`) |
| Hero image / slider | `x-gallery.slider` (`gallery`), single image `x-media.picture` |
| Intro: title + text + image column + CTA | `x-sections.split`, `x-headings.*`, `x-buttons.*` |
| Text page | `x-sections.text` |
| Lage: text, distance table, Google Maps button, map | `pages/location` (`pages-full`), `x-sections.distances`, `x-map` |
| Ausbau: spec list/accordion + PDF buttons | `building-spec`, `downloads` |
| Angebot: filters + isometry + table(s), grouped by house | `apartments` (+ `isometry`, `apply`); columns/filters/grouping in `config/apartments.php` |
| Kontakt: intro + form + map | `pages/contact`, `contact-form` (fields in `config/contact.php`) |
| Anmeldung (landing page) | `registration` |
| FAQ | `faq` |
| Footer: marketer, legal links, "design by stoz" | `components/layout/footer`, `config/site.php` |

Build desktop to match the design at 1024, then tablet and mobile by the rules. New
components only where no starter part fits; a part every site will need goes back into the
starter.

- Labels and headings are config, not view edits: `apartments.filter_labels`, `apartments.columns`,
  `contact.heading`, `registration.heading`, `apply.label` / `apply.statuses`, `downloads.button`.
- The brief decides what is shown (decimals, which columns, a "Flatfox" button); `domain.md`
  covers what the brief leaves open. A "Flatfox" button with the static driver: `apply` in
  `form` mode, or an open item asking for the Flatfox links.
- Marketer logo or other client marks in the footer: `components/layout/footer.blade.php`,
  SVG/PNG under `public/img`.

## 4. Check

`npm run screenshots -- <page URLs>` → `storage/screenshots`. Compare the 1024 shots with the
brief side by side and fix differences; then show Marcel 390 and 768 (where judgement is needed).
Done when every page is ✓ at all five widths and the 1024 shots match the brief.
