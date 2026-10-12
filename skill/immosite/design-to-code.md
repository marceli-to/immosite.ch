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

Done when every magenta annotation of the brief is in one of the three lists.

## 2. Tokens (show to Marcel before building pages)

- Colours and fonts into the `@theme` block of `resources/css/app.css` (roles, never colour
  names); Typekit URL into `config/site.php` → `fonts.stylesheet`; mail colours into
  `resources/views/vendor/mail/html/themes/default.css`; `site.theme_color`.
- Font sizes: set the px values of the scale steps (`--text-xs` … `--text-8xl`, no `base`) so the
  design sizes land on steps; mobile sizes follow `docs/responsive-rules.md` §2.
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

## 4. Check

`npm run screenshots -- <page URLs>` → `storage/screenshots`. Compare the 1024 shots with the
brief side by side and fix differences; then show Marcel 390 and 768 (where judgement is needed).
Done when every page is ✓ at all five widths and the 1024 shots match the brief.
