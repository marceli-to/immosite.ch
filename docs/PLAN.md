# immosite – Starter Repo + Skill for Real Estate Sites

Handoff document for building a reusable starter (`immosite.ch`) and a Claude Code skill that sets up new agency real estate sites from it.

Written 2026-10-12, based on an analysis of 9 existing projects and 4 designer briefs (PDF).

---

## 1. Background

I build the same kind of site for agencies again and again: Laravel apps with few features, mostly real estate. Often they start as a landing page (info, contact/registration, map) and later grow into a full site with apartment listings, sometimes fed by an API (Flatfox or Melon).

### Existing projects analysed

| Repo | Generation | Stack | Notes |
|---|---|---|---|
| adlerstrasse-basel.ch | old | Laravel 10, Statamic 4, Tailwind 3, Vue/Alpine | Flatfox. Debug code left in `FetchData` (appends to `apartements.txt` on every fetch) |
| rheinfelderstrasse-basel.ch | old | Laravel 10, Statamic 4, Tailwind 3 | Flatfox, nearly identical to adlerstrasse |
| radial-rupperswil.ch | middle | Laravel 12, Statamic 5, Tailwind 4 | Flatfox |
| aurea-reichenburg.ch | current-ish | Laravel 10, Blade, Livewire 3, Tailwind 3 | Landing page: Livewire `CreateInquiry`, `SendWeeklyInquiries`, honeypot |
| imwaldacher.ch | current | Laravel 13, Blade, Livewire 4.3, Tailwind 4, Mapbox GL, Swiper 14 | **Landing page**: registration, weekly export, sitemap, disclaimer |
| hueningerstrasse.ch | current | Laravel 13, Blade, Livewire 4, Tailwind 4, Swiper 12 | **Full site**: static apartments in `config/estate.php` + filters, commercial objects, contact form, weekly export, `config/seo.php` noindex + robots route + `RobotsTag` middleware |
| sennheimerstrasse-basel.ch | old | Laravel 10, Statamic 4, Livewire 3, Tailwind 3 | Flatfox. **Sister project** of adlerstrasse/rheinfelderstrasse (same layout, different colours) |
| scala-zuerich.ch | current-ish | Laravel 10, Blade, Livewire 3, Tailwind 3 | Melon (`file_get_contents`, no error handling), isometry, FAQ, disclaimer, `CreateInquiry` ("Bewerben") |
| shift-altstetten.ch | current | Laravel 12, Blade, Tailwind 4, Alpine, Swiper 12 | **Full site**: Melon API (`config/melon.php`, mock fixture, `SendLeadToMelon` lead hook), commercial-object admin with login, contact controller + mails |

### What I found

- Three stack generations, all made by copying the previous project and changing it. Shared code drifts (e.g. `Registration` has a `message` field in hueningerstrasse but not in imwaldacher).
- **Pages that keep coming back:** Projekt/Home, Wohnen/Gewerbe, Lage (map), Kontakt, Danke, Impressum, Datenschutz (sometimes Disclaimer, FAQ, Facts, Galerie, Ausbau).
- **Building blocks that keep coming back:** `x-layout.*` (html/head/body/header/footer), menu, map, Swiper gallery/carousel, headings h1–h3, buttons, form inputs, icon components.
- **API data:** the same `FetchData` / `GetData` pair writes to `storage/public/apartements.json` (the typo has been copied everywhere). The Flatfox version pages through results using `file_get_contents` with no error handling. Melon is a separate version (flat array, uses `Http::`, has a mock).
- Robots/noindex/sitemap exist in some projects but not others.
- **Sister projects / reskins:** adlerstrasse, rheinfelderstrasse and sennheimerstrasse (all A27, Apleona) use the same layout. The designer only sent a sheet with fonts + colours ("Übersicht Website") for the second and third. In the code the reskin was done by find & replace on colour names (`text-steel` → `text-sage` in every heading, button, table…). → Colour tokens need **semantic** names, so that a reskin only touches `@theme`.
- **Isometry** (interactive building graphic in the offer) appears several times: radial (`iso.js`), scala (`iso.blade.php`), hueningerstrasse (`iso.js` + `iso.css` + generator `resources/isometrie/build_isometrie.py` AI→SVG with a README). It's its own module with the most effort in it.
- ⚠️ `hueningerstrasse.ch/routes/console.php`: the weekly export is still set to `16:00` ("TEMPORÄR … zurück auf '08:00'!"). Fix this on live.

---

## 2. Decisions

1. **Starter repo + skill, not just a skill.** Shared code lives in a real repo that boots, can be tested and is upgraded in one place. The skill holds the process (intake, scaffolding, adding/removing modules, go-live) and the domain knowledge. It does not hold code.
2. **One repo, not separate landing and full-site repos.** A landing page is a subset of a full site, and projects often grow from one to the other. Two repos would bring the drift back.
3. **The default state is the landing page.** Full-site features are modules you add.
4. **Optional features are handled in two ways:**
   - **Config flags** for small settings (noindex, mail recipients, optional form fields).
   - **Removable modules** for features (files + hooks). Each project only contains what it uses. No dead code behind flags.
5. **Modules are described in a manifest** (`modules.md` in the starter). Each module lists its files, hooks, config/env and dependencies (`needs:`). The skill uses this list to remove modules (at scaffold time) and to add them back later (copied from the starter).
6. **Modules stay separate:** each module touches shared code in as few places as possible, ideally one line (scheduler line, route, `@include`, nav item).
7. **Old Statamic sites stay as they are.** They are only touched when they need work anyway.
8. **Later, optional:** a private Composer package for the API/registration code, but only if the same bug ends up being patched in several live sites.

---

## 3. Starter repo: `immosite.ch`

### Stack (current generation)
- Laravel 13, PHP 8.4
- Blade + Livewire 4 (forms), Alpine where needed
- Tailwind 4 (design tokens in one place: colours, fonts, spacing in `resources/css/app.css` `@theme`)
- Vite, Swiper, Mapbox GL
- Base the code on **hueningerstrasse.ch** (structure, components, seo, registration) and **shift-altstetten.ch** (Melon, admin, contact mails), plus the **imwaldacher.ch** sitemap/disclaimer/landing layout.

### Core (always there)
- Layout components: `x-layout.html/head/body/header/footer/inner`, menu (desktop + mobile burger)
- Headings, buttons, links, icons (generic set; project-specific icons get added per project)
- Pages: home, Impressum, Datenschutz (+ optional Disclaimer)
- SEO: `config/seo.php` (`ROBOTS_NOINDEX`), `/robots.txt` route, `RobotsTag` middleware (X-Robots-Tag), `/sitemap.xml` built from one list of pages, meta/OG tags in the head
- Favicon/OG image placeholders
- `CLAUDE.md` with conventions (see §5)
- `modules.md` manifest (see §4)

### Modules (optional)

| Module | Needs | Contents (from) |
|---|---|---|
| `registration` | – | Livewire registration form, `Registration` model + migration, confirmation mail, honeypot, optional fields via config (`message` etc.) (imwaldacher / hueningerstrasse) |
| `weekly-export` | registration | `ExportWeeklyRegistrations` command, `WeeklyRegistrationsExport` notification, mail view, scheduler line, `REGISTRATIONS_EXPORT_EMAIL` / `_BCC` |
| `contact-form` | – | Contact form + owner/user mails (shift `ContactController` / hueningerstrasse `ContactForm`), Danke page |
| `map` | – | `x-map` component + Mapbox JS module, `MAPBOX_TOKEN`, coordinates/markers in config |
| `gallery` | – | Swiper carousel/gallery components + JS |
| `pages-full` | – | Projekt, Lage, Kontakt, Danke pages + nav entries (turns a landing page into a full site) |
| `apartments` | pages-full | Apartment listing page, table/list + filters (rooms, floor, availability), detail/PDF links. Uses a **driver**: `static` / `flatfox` / `melon` |
| `apartments-static` | apartments | `config/estate.php` data source (hueningerstrasse) |
| `apartments-flatfox` | apartments | Flatfox fetcher: paginated (`next`), `Http::` with timeout, keeps the last good data if a fetch fails, scheduled fetch |
| `apartments-melon` | apartments | Melon v2 fetcher + mock fixture (`MELON_MOCK`) (shift) |
| `melon-lead-hook` | contact-form, apartments-melon | `SendLeadToMelon` (best effort, logs errors, never blocks the form) |
| `isometry` | apartments | Interactive building graphic: SVG generated from the AI file (generator + README from hueningerstrasse), hover list row ↔ floor/apartment highlighted, colours per status (free/reserved/rented/selected) as tokens, `data-iso="<ref>"` ↔ `data-object-number` (hueningerstrasse / radial / scala) |
| `apply` | apartments | "Anmeldung/Bewerben" per apartment: link to the Flatfox application, or our own form with the apartment preselected (scala `CreateInquiry`) |
| `faq` | – | FAQ page, accordion, content from a config/content file (scala, radial) |
| `downloads` | – | PDF buttons (Kurzbaubeschrieb, Facts & Figures, Pläne UG, Umgebungsplan), files in `public/downloads`, "PDF folgt" state |
| `building-spec` | – | Ausbau/Kurzbaubeschrieb as a list or accordion, content from config (adler/scala) |
| `commercial` | pages-full | Commercial objects list (static config or DB) |
| `commercial-admin` | commercial | Login + edit commercial objects, `CreateUser` command (shift) |

All apartment drivers return the **same normalised format** (ref, house, floor, rooms, area, rent/price, status, plan PDF, …), so the views don't depend on the source. Fetched data goes to a cache/JSON file with the name spelled correctly (`apartments.json`).

### Manifest format (`modules.md`)

```md
## weekly-export (optional, needs: registration)
Files:
- app/Console/Commands/ExportWeeklyRegistrations.php
- app/Notifications/WeeklyRegistrationsExport.php
- resources/views/mail/registrations-export.blade.php
Hooks:
- routes/console.php → Schedule::command('registrations:export-weekly')
Config/env:
- REGISTRATIONS_EXPORT_EMAIL, REGISTRATIONS_EXPORT_BCC
```

Rules:
- Every file that belongs to a module is listed. Hooks are marked in code with `// module:<name>` (or `{{-- module:<name> --}}`) so they're easy to find.
- Removing a module also removes the modules that need it. Adding a module checks its `needs:` first.
- After removing modules, the starter must still boot, and `php artisan test` + `npm run build` must pass, for **every** combination the presets produce.

### Presets
- **landing**: core + registration (or contact-form) + map + gallery
- **full**: landing + pages-full + apartments (+ driver) + optional commercial / commercial-admin / weekly-export / melon-lead-hook

---

## 4. Skill: `immosite`

The skill source lives **in the starter repo** (e.g. `skill/immosite/SKILL.md`) and is symlinked into `~/.claude/skills/immosite`, so it updates together with the starter.

### Commands / flows
1. **New site**
   - Intake: project name + domain, landing or full, modules, apartment driver + API URL, mail recipients (contact / export / BCC), map coordinates + markers, fonts + colours, languages (default: German, Swiss spelling, no ß), pages.
   - Clone the starter into the webroot, new git repo, remove unused modules using `modules.md`, fill in config + `.env.example`/`.env`, set the design tokens.
   - Check: `composer install`, `npm install`, `npm run build`, `php artisan test`, page loads locally.
2. **Sister project / reskin** ("like Adlerstrasse, but with these colours"): use an existing immosite project as the base instead of the starter, swap the tokens (from the "Übersicht Website" sheet), logo, content, API URL and recipients. This only works cleanly if the tokens are semantic (§5).
3. **Add module** to an existing project (e.g. landing → full, add weekly export): copy the files + hooks from the starter, using the manifest.
4. **Remove module**.
5. **Go-live checklist**: `ROBOTS_NOINDEX` off on live, mail recipients + `MAIL_*` set, scheduler cron set up (and the export time correct), Mapbox token restricted to the domain, favicon/OG image, sitemap, Impressum/Datenschutz content, test form submission, first API fetch done.

### Domain knowledge in the skill
- Flatfox: response format (`results`, `next` paging), relevant fields, how to map them to the normalised format.
- Melon v2: flat array, field mapping, lead hook payload + Basic Auth.
- How agencies want apartment tables: order as in the Mieterspiegel (no automatic sorting), status labels (frei / reserviert / vermietet), floor labels.
- Conventions (see §5).

---

## 5. Conventions (for `CLAUDE.md` in the starter)
- Routes: German slugs (`/wohnen`, `/lage`, `/kontakt`, `/impressum`, `/datenschutz`, `/danke`), route names `page.*`.
- Views: `resources/views/pages/*.blade.php`, components under `resources/views/components/{layout,menu,headings,buttons,links,form,icons,media,gallery,sections,objects}`.
- Code, comments, docs and commit messages in English. Visitor-facing text (pages, form labels, validation messages, mails, legal pages) in German, Swiss spelling, no ß. Comments explain *why*.
- **Colour tokens are semantic**, never named after the colour: `primary`, `primary-light`, `surface`, `surface-alt`, `ink`, `muted`, `status-free`, `status-reserved`, `status-rented`, … The actual colours only live in `@theme`. (Bad: `text-steel`, `text-sage`, `bg-cocoa`.)
- Fonts are tokens too (`--font-sans`, `--font-display`), and the source (Typekit kit, Google/self-hosted) only goes in the head/config.
- Config per topic (`config/seo.php`, `config/estate.php`, `config/melon.php`, …), secrets/URLs only through `.env`.
- No debug leftovers. Every API fetch has a timeout and error handling and keeps the last good data.

---

## 6. Design to code (desktop design → desktop/tablet/mobile)

Goal: I hand over screenshots (or Figma/PDF) of the **desktop** design, and Claude builds desktop and works out tablet + mobile itself, following my rules.

### What I get
| Source | What Claude gets from it | Note |
|---|---|---|
| Figma (usually desktop only) | Exact values (fonts, sizes, colours, spacing) | If possible connect the Figma MCP (Dev Mode), otherwise screenshots + inspect values |
| InDesign → PDF | Claude can read PDFs directly (layout + text) | Export the PDF from InDesign, don't scan it. Fonts/colours from the style sheet |
| Screenshots | Layout, hierarchy, approximate sizes | Take them at a **known width** (e.g. 1440px) so px values can be measured |

### Designer briefs (e.g. stoz)
Two kinds of PDF have come in so far:
- **"Website-Beschreibung"** (e.g. `A27_Adlerstrasse_Website_Beschreibung.pdf`, `G09_Scala_Website_Beschreibung.pdf`): one desktop mockup per page. **Magenta text = instructions to me, not content**: fonts (+ Typekit link), colours (hex + role, e.g. "hellblau 1 (Hintergrund)"), link targets ("Link: Seite «Wohnen»", Google Maps URL), "Slider: mit drei Bildern", "PLATZHALTER" over images, "Inhalte aus «Kurzbaubeschrieb»-pdf hinterlegen", "PDF folgt", isometry colours.
- **"Übersicht Website"** (e.g. Rheinfelderstrasse, Sennheimerstrasse): fonts + colours only → a **sister project/reskin** of an existing layout.

From a brief, Claude should produce:
1. **Tokens** (fonts, colours with semantic role).
2. **Page list + sections** per page, mapped to modules/components.
3. **Open items list** from the annotations: placeholder images, missing PDFs, "Pläne folgen", content to take from other files (Kurzbaubeschrieb, FAQ file, Mieterspiegel). That's the list of things to get from the client.

### Section catalogue (from the briefs so far)
Components the starter should have, because they keep coming back:
- **Header/nav:** horizontal bar with logo on the right (A27) or a nav box top right + logo top left (scala). Active state.
- **Hero:** single image or slider (n images, arrows), sometimes with a stepped edge (scala).
- **Intro:** H1 + optional subtitle (light) + optional lead (bold) + text, image column on the right (1–3 images stacked), CTA button.
- **Lage:** text + image(s), **distance table** (destination × on foot/bike/train/car), Google Maps button, map across the full width.
- **Ausbau:** spec list or accordion + PDF buttons.
- **Angebot:** filters (Verfügbarkeit, Zimmer, Hausteil, Etage, Zurücksetzen) + isometry + table(s), optionally **grouped by house**. Columns differ per project (Nr., Etage, Zi., HNF/Wohnfläche, AF/Balkon, Brutto/Netto MZ, Nebenkosten, Grundriss ↓, Status/Anmeldung/Bewerben), footnote "Alle Kosten in CHF", extra PDF buttons.
- **Kontakt:** intro + form (Vorname, Name, Strasse/Nr., PLZ/Ort, E-Mail, Telefon, Nachricht, privacy checkbox, Absenden) + map.
- **FAQ:** intro + accordion.
- **Footer:** property manager address + logo, Disclaimer/Impressum/Datenschutz, "design by stoz".

→ The table columns, filters and grouping must be **configurable** (config per project), not rebuilt every time.

**Always needed per project** (when it's not in Figma): font names + weights (+ Adobe Fonts/webfont source), colours as hex, logo/icons as SVG, images in original quality.

### Steps (part of the skill, reference file `skill/immosite/design-to-code.md`)
1. **Set up the design tokens once** per project: fonts, colours, type scale (desktop + mobile), container width, gutters → Tailwind `@theme`. Show them to me before building pages.
2. **Break each page into sections** and map them to existing components (headings, gallery, map, apartment table, …). Only build new components where needed.
3. **Build desktop** to match the design.
4. **Work out tablet + mobile** using the rules below.
5. **Check:** run locally, take screenshots at 390 / 768 / 1280 / 1440px (preview/browser tools), compare desktop with the design and fix differences. Then show me mobile + tablet for review (this is where judgement is needed).

### Responsive rules (my rules; the most important part, **to be filled in by me**)

Seen in existing projects (not consistent yet, make it the standard in the starter):
- Spacing unit 1px (`px-18` = 18px), values taken straight from the design. In Tailwind 4: `--spacing: 1px` (radial lists 250 `--spacing-*` variables one by one; don't do that).
- Container: `max-w-* mx-auto`, padding ~18–20px mobile → 24–30px desktop.
- Headings: mobile ≈ 60–75% of desktop (hueningerstrasse 31.2 → 51.9px, shift 30 → 40px).
- Usually 2 steps (`md`), sometimes 3 (`md`/`lg`/`xl`).

To be filled in by me:
- [ ] Breakpoints: which ones, and is tablet more like desktop or mobile?
- [ ] Type scale: fixed ratio desktop → mobile per level (h1/h2/h3/body/small)? Body text size on mobile?
- [ ] Vertical spacing between sections: desktop → mobile ratio?
- [ ] Order: text/image pairs on mobile (image first or text first)? Multi-column grids → 1 column, or 2 on tablet?
- [ ] Navigation: from which breakpoint burger? Overlay or slide-in?
- [ ] Apartment table on mobile: cards, horizontal scroll, or fewer columns?
- [ ] Images: change aspect ratios on mobile (e.g. wide → 4:3/square)? Full width (no container padding)?
- [ ] Galleries/sliders: same behaviour or simplified?
- [ ] Map: height/aspect on mobile, interaction (scroll zoom off)?
- [ ] Things I always do that aren't in the design (hover states, transitions, fade-ins, sticky header…)?

Tip: the fastest way to fill these in is a **pair** of desktop design + my finished mobile version from an earlier project (e.g. hueningerstrasse). Claude can derive the rules from it and I just correct them.

---

## 7. Next steps
1. ✅ Write this plan.
2. ✅ Create `immosite.ch` in the webroot + git repo (me).
3. New Claude session in that folder, with this file as the briefing:
   1. ✅ Copy the source repos (hueningerstrasse, shift, imwaldacher, scala, adlerstrasse) in as read-only references (outside the repo or git-ignored). The 4 brief PDFs are in `docs/briefs/` (git-ignored, the repo is public). The skill reads them from there as examples; don't copy them into `skill/`.
   2. ✅ Build the core starter (landing preset) and get it running. Write `CLAUDE.md` + `modules.md` alongside.
   3. ✅ (draft, defaults confirmed 2026-10-12) Fill in the responsive rules (§6) **before the modules**: they affect every component, so build them in from the start instead of retrofitting. Derive them from 1–2 earlier projects (desktop design + live site, e.g. `A27_Adlerstrasse_Website_Beschreibung.pdf` ↔ adlerstrasse-basel.ch, `G09_Scala_Website_Beschreibung.pdf` ↔ scala-zuerich.ch), then I correct them. Apply them to the core components.
   4. ✅ Add the modules one at a time, each with its manifest entry, tests and a remove check.
   5. ✅ Write the skill (`skill/immosite/SKILL.md` + `design-to-code.md`) + symlink it.
   6. ✅ Trial run: scaffold a test landing page and a test full site with the skill, then compare the results.

---

## 8. Status and how to pick up (updated 2026-10-12)

**Done:** starter with core + 22 modules (`modules.md`), 117 tests, skill `immosite`
(`skill/immosite`, symlinked to `~/.claude/skills/immosite`), two trial runs (landing + Adlerstrasse
brief, findings fixed, test sites deleted), Shift patterns added (hero + stats, split bleed,
columns, scroll-reveal), pattern library `/muster`.

**Where things are:**
- Conventions: `CLAUDE.md`. Modules and hooks: `modules.md`. Responsive rules: `docs/responsive-rules.md`.
- Process for new sites: `skill/immosite/` (start prompt: `docs/new-project-prompt.md`).
- Reference repos (read-only, git-ignored): `refs/` (adlerstrasse, aurea, hueningerstrasse, imwaldacher, radial, rheinfelderstrasse, scala, sennheimerstrasse, shift). Briefs: `docs/briefs/` (git-ignored).
- Tools: `php artisan module:remove`, `bin/check-module-removal`, `npm run screenshots`, `php artisan isometry:build`, `skill/immosite/scripts/brief.swift`.
- Local: https://immosite.ch.test (Herd), push over SSH (`git@github.com:marceli-to/immosite.ch.git`).

**Decided along the way (not obvious from the code):**
- Developer text in English, visitor text German (Swiss, no ß).
- Commit and push after each finished step.
- Design 1024 px = `lg` 1:1, `--scale` 1.15 from `xl`; burger by number of nav entries; scroll reveal off by default; map: two-finger pan.
- Machine formats (XML, CSV, JSON) in PHP, not Blade.
- A recurring pattern goes into the starter as a component + pattern file + test.
- Intake: Claude asks the questions (AskUserQuestion), Marcel doesn't fill in a template.

**Open:**
- Hero mobile ratio (4:3) and slider autoplay (6 s) are defaults, not confirmed (`docs/responsive-rules.md` ❓).
- First real project with the skill; expect corrections, feed them back.

**2026-10-14:** webroot deep dive done (`docs/webroot-scan.md` → "Deep dive", 22 ranked
candidates). Built: Flatfox collective-listing filter, owner mail per registration, gallery
grid + lightbox, parking/extras table, application for several apartments, mobile apartment
list with details synced to the isometry. Not now: unit admin, multi-building isometry,
French, isometry floor-plan mode.

**Previous task (done):** webroot scan for more projects and patterns. The inventory is done
(`docs/webroot-scan.md`: 63 stoz / real-estate projects, grouped); the deep dive per group is
described at the end of that file and hasn't started. **Selection rule: only projects whose `INSTALL.txt` has `ssh … -p 2121` (= stoz).** No credentials from `INSTALL.txt` in the repo.
