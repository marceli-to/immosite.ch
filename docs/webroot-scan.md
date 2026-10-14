# Webroot scan (started 2026-10-12, deep dive 2026-10-14)

Inventory of `/Users/marceli.to/Jamon.digital/Webroot` to find more projects and patterns for the starter.
Generated from `composer.json`, `package.json`, a keyword grep of `resources/` and `git log`.
**No server data in this file**: hosts, users and passwords from `INSTALL.txt` stay out of the repo.

## Selection rule (Marcel, 2026-10-12)

**A project is a stoz project when its `INSTALL.txt` contains an SSH line with port 2121**
(`ssh <user>@<host> -p 2121`). Only those count; every project has an `INSTALL.txt`.
The grouping further down was made from the keyword "stoz" in the code and is **superseded**:
redo it from this rule (the first pass found only 180 `INSTALL.txt` files with
`find -maxdepth 2 -iname INSTALL.txt`; check for other names/locations before trusting that).

Projects on port 2121 found in the first pass (38, to verify):

- adlerstrasse-basel.ch
- ai-s.ch
- aurea-reichenburg.ch
- bachofner-kanal.ch
- bambole.ch
- creativefactory-renens.ch
- drive-academy.ch
- faw.ch
- hueningerstrasse.ch
- landing.aupark-reinach.ch
- landing.pappelstrasse.ch
- landing.ruem.ch
- landing.werkwinkel.ch
- lindenbaum.ch
- luppmenpark.ch
- magnolia.ch
- nidum-sempach.ch
- nineone.ch.landing
- pandaundpinguin.ch
- pappelstrasse.ch
- pek.ch
- pfaeffikermaess.ch
- pietsch-haustechnik.ch
- rebo-bm.ch
- rebobau.ch
- restaurant-satt.ch
- rheinfelderstrasse-basel.ch
- ruem.ch
- sandtner.ch
- scala-zuerich.ch
- siewerdtstrasse.ch
- stiftung-wunderland.ch
- tiny-isch-trendy.spross.com
- treuhand-weiss.ch
- typo3.altorfer-ag.ch
- viak.ch
- werkwinkel.ch
- zuerigaertner.ch

Not on port 2121 but in the keyword list (so **not** stoz by the rule, e.g. older Vue-generation
real-estate sites like nineone.ch, oerlikon.ch, muenchhaldenstrasse.ch, mps.ch, kornhaus-roetelstrasse.ch):
check whether they moved hosting or are really not stoz before dropping them.

## Numbers

- 292 project folders; 180 with an `INSTALL.txt` (formats vary: GitHub URL, PHP path, SSH line).
- 224 Laravel (5–13, most 9–12), 71 with Statamic, 34 Livewire, Tailwind 3 (96) / 4 (52).
- 48 mention stoz in the code; 63 are stoz by keyword or show real-estate signals (old grouping, see the selection rule).
- SSH port 2121: 38 projects, 27 of them stoz; 27 of the 48 stoz projects use it. Marcel's rule of thumb ("port 2121 = stoz") mostly holds.

Flags: `flatfox`, `melon`, `iso` (isometry), `apt` (apartment/rental words), `map` (Mapbox), `reveal` (scroll animation), `form`.

## A. Real estate by stoz, not in refs/ yet (main pattern sources)

| Project | Last commit | Stack | Flags |
|---|---|---|---|
| pappelstrasse.ch | 2026-06-01 | Laravel 12, TW4 | flatfox iso apt map reveal |
| landing.pappelstrasse.ch | 2026-01-28 | Laravel 12, Vue, TW4 | apt map form |
| magnolia.ch | 2026-02-19 | Laravel 10, Statamic 4, TW3 | iso apt map |
| siewerdtstrasse.ch | 2026-02-05 | Laravel 10, Statamic 4, TW3 | iso apt map |
| werkwinkel.ch | 2026-02-06 | Laravel 10, Statamic 5, TW3 | iso map |
| landing.werkwinkel.ch | 2025-02-01 | Laravel 10, Livewire, TW3 | apt map form |
| creativefactory-renens.ch | 2026-02-05 | Laravel 10, Statamic 4, TW3 | flatfox iso apt map |
| luppmenpark.ch | 2026-02-05 | Laravel 10, Livewire, TW3 | apt form |
| kornhaus-roetelstrasse.ch | 2026-02-06 | Laravel 10, Statamic 5, TW3 | apt |
| nidum-sempach.ch | 2025-02-01 | Laravel 9, Vue | flatfox iso apt map form |
| landing.aupark-reinach.ch | 2024-01-16 | Laravel 8, Vue | apt map form |
| mps.ch | 2025-02-01 | Laravel 9, Vue | apt map reveal form |
| lindenbaum.ch | 2026-02-06 | Laravel 10, Statamic 5, Livewire, Vue, TW3 | apt map reveal form |
| nineone.ch | 2020-03-25 | Laravel 6, Vue | iso apt map form |
| nineone.ch.landing | 2019-10-01 | Laravel 5, Vue | apt map form |
| oerlikon.ch | 2020-03-25 | Laravel 6, Vue | iso apt map form |
| muenchhaldenstrasse.ch | 2020-09-21 | Laravel 6, Vue | iso apt map form |

## B. Real estate, not stoz

| Project | Last commit | Stack | Flags |
|---|---|---|---|
| liegenschaften.aporta-stiftung.ch | 2025-03-12 | Laravel 11, Vue | iso apt form |
| interessenten.aporta-stiftung.ch | 2026-06-15 | Laravel 13, Vue, TW4 | apt form |
| aporta-stiftung.ch | 2026-02-06 | Laravel 10, Statamic 5, Vue, TW3 | apt form |
| riccardacavelti.ch | 2025-03-23 | Laravel 11 | apt form |
| 14-zimmer-oberwil-lieli.riccardacavelti.ch | 2024-03-11 | Laravel 9, Vue | apt form |
| 8.5-zimmer-oberwil-lieli.riccardacavelti.ch | 2024-03-06 | Laravel 9, Vue | apt form |
| 5.5-zimmer-oberwil-lieli.riccardacavelti.ch | 2024-03-06 | Laravel 9, Vue | apt form |
| spiserstrasse.ch | 2025-06-06 | Laravel 9, Vue, TW3 | iso apt form |
| bbr-ag.ch | 2025-06-27 | Laravel 9 | apt form |
| landing.baumbergerstegmeier.ch | 2023-03-30 | Laravel 9, Vue, TW3 | apt form |

## C. Already in refs/ (starter sources)

| Project | Last commit | Stack | Flags |
|---|---|---|---|
| adlerstrasse-basel.ch | 2026-02-05 | Laravel 10, Statamic 4, TW3 | flatfox iso apt map |
| rheinfelderstrasse-basel.ch | 2026-02-06 | Laravel 10, Statamic 4, TW3 | flatfox iso apt map |
| radial-rupperswil.ch | 2025-11-19 | Laravel 11, Statamic 5, Livewire, TW3 | flatfox iso apt map |
| aurea-reichenburg.ch | 2026-03-10 | Laravel 10, Livewire, TW3 | apt map form |
| imwaldacher.ch | 2026-07-07 | Laravel 13, Livewire, TW4 | apt map form |
| hueningerstrasse.ch | 2026-02-05 | Laravel 10, Livewire, TW3 | apt map form |
| scala-zuerich.ch | 2026-02-05 | Laravel 10, Livewire, TW3 | iso apt map form |

## D. stoz, not real estate (company sites; general patterns only)

| Project | Last commit | Stack | Flags |
|---|---|---|---|
| ahorngarten.ch | 2022-11-16 | Laravel 9, Vue | map form |
| altorfermetallbau | 2019-12-20 | Laravel 5, Vue | map |
| archi-nova.ch | 2025-03-19 | Laravel 11, Vue | map reveal form |
| ate-bus.ch | 2026-03-11 | Laravel 12, Statamic 5, Vue, TW4 | map reveal form |
| bachofner-kanal.ch | 2026-02-06 | Laravel 11, Statamic 5, Vue, TW3 | map |
| drive-academy.ch | 2025-03-20 | Laravel 10, Statamic 5, Vue, TW3 | form |
| flowerpower.spross.ch | 2026-02-06 | Laravel 10, Livewire, TW3 | reveal |
| hrglas.ch |  | Laravel 5, Vue | reveal |
| industriekultur-neuthal.ch | 2026-02-19 | Laravel 10, Statamic 5, TW3 | form |
| inmara.com | 2026-02-06 | Laravel 10, Statamic 5, TW3 | map form |
| menschenbegleiten.ch | 2025-02-07 | Laravel 11, TW3 |  |
| moro-genki.ch | 2026-02-06 | Laravel 10, Statamic 5, TW3 | reveal |
| pek.ch | 2025-03-26 | Laravel 11, Vue | map reveal form |
| pfaeffikermaess.ch | 2026-02-05 | Laravel 10, Livewire, TW3 | map |
| pietsch-haustechnik.ch | 2026-06-23 | Laravel 12, TW4 | apt reveal |
| rebo-bm.ch | 2025-02-01 | Laravel 10, Statamic 5, TW3 | map reveal |
| rebobau.ch | 2025-02-01 | Laravel 10, Statamic 5, TW3 | map reveal |
| sandtner.ch | 2026-02-06 | Laravel 10, Statamic 5, TW3 | map form |
| senntiefbau.ch | 2026-02-25 | Laravel 12, TW4 | apt |
| skilift-oberwangen.com | 2026-02-06 | Laravel 10, Statamic 5, TW3 | map form |
| stoz.ch | 2026-02-06 | Laravel 10, Statamic 5, TW3 | map |
| treuhand-weiss.ch | 2025-04-15 | Laravel 10, Statamic 5, Vue, TW3 | apt map |
| ueberland-funk.ch | 2026-02-06 | Laravel 10, Statamic 5, TW3 |  |
| weberbrunner.eu.import |  | Laravel 10, Statamic 5, Livewire, TW3 |  |
| zuerigaertner.ch | 2025-11-14 | Laravel 9, Statamic 3, Vue, TW3 | map |

## Deep dive (2026-10-14)

Scope: real-estate projects on port 2121 that are not in `refs/` yet: pappelstrasse.ch,
landing.pappelstrasse.ch, magnolia.ch, siewerdtstrasse.ch, werkwinkel.ch, creativefactory-renens.ch,
landing.werkwinkel.ch, luppmenpark.ch, landing.aupark-reinach.ch, nineone.ch.landing, nidum-sempach.ch.
lindenbaum.ch was checked too and is **not** real estate (social institution); ruem.ch / landing.ruem.ch
neither. Read-only, no `INSTALL.txt` / `.env` opened. Paths are relative to the Webroot.

General finding: the old sites hard-code what the starter keeps in config, fetch APIs during the
request without timeout, and have no lead retention. The starter is ahead on robustness; the gaps
are features.

### Starter bug

- **Flatfox collective listing not filtered.** pappelstrasse drops listings with `ref_house === '99'`
  (Sammelinserat, `pappelstrasse.ch/app/Actions/GetData.php:42`). `FlatfoxSource::normalise()` doesn't,
  so a "99-9996" placeholder row would show in the table. Fix: `flatfox.exclude_houses => ['99']`.

### Candidates (ranked by how often they appeared × cost)

| # | Candidate | Seen in | Where it goes |
|---|---|---|---|
| 1 | **Lightbox** for gallery grid / images (keyboard, groups) | pappelstrasse, magnolia, siewerdtstrasse, werkwinkel, creativefactory, luppmenpark (+ adlerstrasse) | `gallery` option: `x-gallery.grid` + lightbox |
| 2 | **Owner notification per lead** (mail to agency on each registration, optional JSON/CSV) | landing.werkwinkel, nineone.ch.landing, aupark (unused) | `registration` config `notify` |
| 3 | **Consent timestamp + retention** (`privacy_accepted_at`, `registrations:prune --months`) | none (gap everywhere; nDSG) | `registration` / `contact-form` |
| 4 | **Several units per inquiry** (picker fed from the apartment source, removable badges, `?wohnung=` preselects) | magnolia, siewerdtstrasse, creativefactory | `apply` |
| 5 | **Mobile apartment rows as accordion** (status dot, rooms, rent, apply; expands to details + plan), synced with isometry | pappelstrasse, nidum (detail panel) | `apartments` table, mobile view |
| 6 | **Parking / extras table** (type, floor, price/status; static rows) | pappelstrasse, magnolia, siewerdtstrasse | `apartments` config `extras` |
| 7 | **Database driver + small admin for units** (status, price, apply link) — generalise `commercial-admin` | magnolia, siewerdtstrasse, werkwinkel (only thing clients edit in Statamic) | new `apartments-database` |
| 8 | **Floor-plan PDF by naming pattern** (`/downloads/plans/{ref}.pdf` if present) | pappelstrasse, nidum, siewerdtstrasse | `apartments` config `plan_path` |
| 9 | **Per-ref overrides / excludes** (fix rooms/floor, hide a ref, "2.5+1") | creativefactory, siewerdtstrasse (merged units) | `apartments` config `overrides` |
| 10 | **Hide prices for reserved too** (`hide_prices_for: [rented, reserved]`) | nidum, magnolia | `apartments` config |
| 11 | **Flatfox lead hook** (JSON mail to the Flatfox inbox) | landing.pappelstrasse | new `flatfox-lead-hook` (mirror of `melon-lead-hook`) |
| 12 | **Isometry: several buildings** (overview + one drawing per house) | nidum | `isometry` |
| 13 | **Isometry: floor-plan mode** (per-floor plan image instead of stacked lift) | creativefactory | `isometry` option |
| 14 | **Sticker CTA** (round rotated "Störer": "3D-Rundgang", "Alle Wohnungen verkauft", date) | magnolia, siewerdtstrasse, werkwinkel | component `x-sticker` |
| 15 | **Virtual tour** (Matterport embed or teaser image → external tour) | pappelstrasse, werkwinkel | section + pattern |
| 16 | **Numbered footnotes** on table headers + per-row note (SIA 416, "inkl. Gartenanteil") | magnolia, siewerdtstrasse | `apartments` `footnotes` array |
| 17 | **French locale** (`<html lang>` from config, `lang/fr`, status labels, Flatfox `/fr/`, number format) | creativefactory | core: locale switch, no multisite |
| 18 | **Timeline** (milestones: date + text) | luppmenpark | section + pattern |
| 19 | **Contact person / partner logos** block (marketer, owner, developer) from `site.marketer` / `site.partners` | all four landings, aupark | component |
| 20 | **Form types** (one select switches field sets, `request_type` column) | luppmenpark | `registration` / `contact-form` config `types` |
| 21 | **News / construction progress** (date, text, slider, video) — needs editing | magnolia, siewerdtstrasse | later, only with an edit layer |
| 22 | Smaller: hero with scroll-down button + portrait/landscape images, icon feature row, video component, `company` field, minimum fill time on forms | various | options on existing parts |

Not worth taking: Vue/JWT admins (abandoned in nidum and aupark), Rellax parallax, Corcel,
Statamic content backups (only relevant with a file CMS).

### Old-site bugs (already avoided by the starter, keep it that way)

Fetch in request without timeout and overwrite on failure (pappelstrasse, nidum, creativefactory);
raw feed on the public disk (creativefactory); swapped status filter labels (nidum) and a `rented`
filter that never matches `taken` (pappelstrasse); removed Flatfox listings counted as free
(creativefactory); `env()` in controllers; leads stored but nobody notified (luppmenpark); message
field dropped from `only()` (landing.werkwinkel); catch-all route = soft 404 (aupark); GA without
consent; no honeypot (nineone).

### Next

Fix the Flatfox bug, then Marcel picks candidates; each one is built like the Shift patterns
(component + pattern file + test, manifest entry if it's a module).
