# Responsive rules (draft)

How a **desktop-only design** becomes tablet + mobile. Derived from Adlerstrasse and Scala
(brief PDF ↔ code) and Hüningerstrasse / Im Waldacher (current generation), 2026-10-12.
Status: **draft, to be corrected by Marcel.** Items marked ❓ are open decisions.

Becomes `skill/immosite/design-to-code.md` once settled.

## 0. What the briefs give us

- stoz PDFs are **1024 pt wide = 1024 px design width**, desktop only. The PDF text layer has exact font sizes.
- 1024 is Tailwind's `lg`. So: **design values apply 1:1 at `lg`**, everything below is derived.
- Past projects drifted from the design (Adler h2 24 vs 16, Scala all type ×1.2). Rule: match the design at `lg`; deviations are decisions, not accidents.

## 1. Breakpoints

Tailwind defaults, no custom screens (`xs` was defined twice and never used).

| Tier | Range | Role |
|---|---|---|
| mobile | < 768 | one column, mobile sizes |
| tablet | `md` 768–1023 | layout splits into columns, **in-between** sizes |
| desktop | `lg` ≥ 1024 | design values 1:1 |
| wide | `xl` ≥ 1280 | container stops growing ❓ scale up? |

- `sm` (640) only for small item grids (2 columns) and form rows.
- Past projects mixed this (Hüningerstrasse: desktop nav at `md`, mobile layout until `lg`). Standard: **both switch at `md`**, unless the text column would be narrower than ~340 px; then that one section splits at `lg`.

## 2. Type

Mobile size = desktop × ratio, rounded to the nearest step of our scale. Tablet = between the two.

| Desktop size | Ratio mobile/desktop | Example (desktop → tablet → mobile) |
|---|---|---|
| ≥ 40 px (h1, big h2) | **0.6** | 48 → 40 → 28 |
| 24–39 px (h2) | **0.7** | 32 → 28 → 24 |
| 17–23 px (h3, lead, buttons) | **0.9** | 20 → 20 → 18 |
| ≤ 16 px (body, nav) | **1.0**, body never below 16 | 16 → 16 → 16 |
| table / small | **0.85**, never below 12 | 14 → 14 → 12 |

Observed: Adler h1 0.59, Scala h1 0.42 (too small), Hüningerstrasse h1 0.60, body 0.89–0.94 everywhere.

- Line height: headings 1.1–1.2, body as in design (usually 1.3–1.45). Don't change it between breakpoints.
- `text-balance` on headings; `hyphens-auto` + `&shy;` for long German compound words on mobile.

## 3. Vertical spacing

| What | Ratio mobile/desktop | Tablet |
|---|---|---|
| Section padding (between bands) | **0.5** | 0.75 |
| Gaps inside a section (grid top margin, stacked images) | **0.5** | 0.75 |
| Text-level (heading margin, paragraph margin) | **0.8** | 1.0 |

Observed: Adler 0.5 / 0.8, Scala 0.375 (too tight) / 0.8, Hüningerstrasse 0.62.
Example: section `py-40 md:py-60 lg:py-80`, paragraph `mb-16 md:mb-20`.

## 4. Horizontal

- Container max width = content width of the design (Adler 928, Scala 864 at 1024). Centered.
- Side padding: **20 mobile → 30 tablet → design margin at `lg`** (stoz: 48–80 px). Keep ≥ 20 on mobile.
- Grid gap: design value at `lg`, **0.5 at `md`**, rows stack on mobile with the section-gap spacing.

## 5. Order and columns

- **Mobile follows DOM order: text first, image second.** Write the DOM in reading order; only use `order-*` for exceptions (e.g. logo right on desktop).
- Text/image pairs: 1 column → split at `md` (ratio from design, e.g. 7/5).
- Small items (filters, cards, form fields, footer blocks): 1 → 2 at `sm` → design count at `lg`.
- Images that bleed to the viewport edge on desktop stay inside the container padding on mobile.

## 6. Navigation

- Burger below `md` ❓ (Adler, Hüningerstrasse: `md`; Scala: `sm`). Check that the desktop nav fits at 768; if not, burger below `lg`.
- Mobile menu: **full-screen overlay**, background `surface-alt`, opacity fade (200–300 ms), Escape closes, **page scroll locked** while open (Adler/Scala didn't), real `<button>` with `aria-expanded`.
- Header: `sticky`, solid background. Transparent over the hero only when the design shows it (Hüningerstrasse home: background fades in after 30vh).
- Anchors clear the sticky header: `scroll-mt-<header height>`.

## 7. Apartment table

- **Horizontal scroll, all columns kept** (`overflow-x-auto`). No cards. Adler/Scala squeezed 7–9 columns into 390 px without a scroll wrapper; Hüningerstrasse's scroll version is the standard.
- Below `md`: shortened headers ("Zi.", "Fläche", "Aussen", "Plan"), cell padding 10 instead of 20, text 12–13.
- Sticky table header.
- Isometry on mobile: sticky **above** the table, max ~45vh, background so rows don't show through. Check the sticky offset math (Adler overlapped by 10 px). Touch: tap on isometry highlights the row.

## 8. Images

- Hero: full-bleed at every width. ❓ Mobile ratio: past projects kept the desktop ratio (21:9 / 16:8 → 167–195 px tall at 390, too flat). Proposal: **4:3 mobile, 16:9 tablet, design ratio at `lg`**, `object-cover`, optional separate `-mobile` crop.
- Content images: keep their ratio, inside the container.
- Always: correct `width`/`height` attributes (Adler + Scala had portrait values on landscape images → layout shift), correct `type` per `<source>`.

## 9. Sliders and galleries

- Same behaviour on all sizes: swipe, loop, arrows always visible (tap target ≥ 44 px), dots optional.
- Autoplay 5–7 s was used everywhere; ❓ keep as default, paused on interaction.
- Gallery thumbnails → lightbox, grid 1 → 2 (`sm`) → design count (`lg`).

## 10. Map

- Full width. Height by aspect: **1:1 mobile, 16:9 tablet, design ratio at `lg`** (design ≈ 3:1). Past 16:8 on mobile was fine, 16:4 at 2xl too flat.
- Scroll zoom off, zoom buttons on.
- Touch ❓: `cooperativeGestures: true` (two-finger pan, Waldacher) vs. dragging off entirely (Hüningerstrasse). Proposal: cooperativeGestures.

## 11. Always added (not in the design)

- Hover: colour change on links, buttons, nav; table rows `hover:bg-ink/5`. `transition-colors` 150–300 ms.
- `focus-visible` outline on everything interactive.
- `scroll-smooth` on `<html>`.
- Accordions animate (Alpine collapse). Scala's didn't.
- ❓ Scroll reveal (fade-in on scroll): only Hüningerstrasse had it. Default off?

## 12. Checks before showing it

- Screenshots at 390 / 768 / 1024 / 1280 / 1440; compare 1024 with the PDF.
- Nothing overflows horizontally at 390 (except the table's own scroll).
- Tap targets ≥ 44 px, body ≥ 16 px, table ≥ 12 px.
- Mobile menu: opens, closes on Escape and link click, page doesn't scroll behind it.
- Sticky elements don't overlap.
