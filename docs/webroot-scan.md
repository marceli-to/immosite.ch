# Webroot scan (started 2026-10-12, paused)

Inventory of `/Users/marceli.to/Jamon.digital/Webroot` to find more projects and patterns for the starter.
Generated from `composer.json`, `package.json`, a keyword grep of `resources/` and `git log`.
**No server data in this file**: hosts, users and passwords from `INSTALL.txt` stay out of the repo.

## Numbers

- 292 project folders; 180 with an `INSTALL.txt` (formats vary: GitHub URL, PHP path, SSH line).
- 224 Laravel (5–13, most 9–12), 71 with Statamic, 34 Livewire, Tailwind 3 (96) / 4 (52).
- 48 mention stoz; 63 are stoz or show real-estate signals.
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

## Next session: deep dive

Read-only, never open `INSTALL.txt` / `.env`. Compare every project with `modules.md` and the
"Brief section → Starter part" table in `skill/immosite/design-to-code.md`; report only what the starter lacks:
new patterns (with file paths, reusable or not), variations of existing parts worth an option,
CMS needs (what clients edit in Statamic), bugs or better solutions. Suggested split (parallel agents):

1. pappelstrasse.ch, landing.pappelstrasse.ch, magnolia.ch, siewerdtstrasse.ch (newest generation)
2. werkwinkel.ch, landing.werkwinkel.ch, creativefactory-renens.ch (French?), luppmenpark.ch, kornhaus-roetelstrasse.ch (Statamic)
3. nidum-sempach.ch, landing.aupark-reinach.ch, mps.ch, lindenbaum.ch, nineone.ch, oerlikon.ch, muenchhaldenstrasse.ch (Vue generation: what did the old apartment lists have?)
4. group B (aporta: portfolio + interested-party management; riccardacavelti: sales sites)

Then: rank the candidates, decide with Marcel which go into the starter, build them like the Shift patterns (component + pattern file + test).
