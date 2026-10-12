# Domain knowledge

## How agencies want the apartment table

- **Order as in the Mieterspiegel**, never re-sorted (`apartments.sort = 'source'` for static data).
  API data has no Mieterspiegel order: `'natural'` (house, floor, ref).
- Status labels **frei / reserviert / vermietet** (`apartments.status_labels`; "verkauft" for sales).
- **No rent for rented apartments** (`hide_prices_when_rented`).
- Floor labels: UG, EG, 1. OG, 2. OG, Attika (`apartments.floors`, source value → label).
- Usual column names: Nr., Etage / Geschoss, Zimmer (Zi.), Wohnfläche / HNF m², Aussenfläche /
  Balkon / AF m², Nettomiete / Netto MZ, Nebenkosten (NK), Bruttomiete / Brutto MZ, Grundriss ↓,
  Status / Anmeldung / Bewerben. Footnote "Alle Kosten in CHF pro Monat." Some group by house
  with the address as heading (`group_by_house`, `houses`).
- Areas rounded **up** to whole m², money with apostrophe (2'070).

## Flatfox (driver `apartments-flatfox`)

- Public API of the agency, e.g. `https://flatfox.ch/api/v1/public-listing/?organization=<id>&expand=documents`;
  paged: `results` + `next` (URL of the next page or null).
- Fields used: `pk`, `reference`, `ref_house`, `ref_object`, `status`, `reserved`, `object_category`,
  `floor` (0 = EG, -1 = UG), `number_of_rooms` (string), `surface_living`, `surface_balcony`,
  `rent_net`, `rent_charges`, `rent_gross`, `price_display`, `offer_type`, `moving_date`,
  `short_url`, `documents[].url`.
- Status: `pre`/`act`/`dis` = published (`reserved` true → reserviert, else frei); `arc`/`rem` = vermietet.
- A listing can be re-published: same `reference`, new `pk`. Keep the higher `pk`.
- Apply link: `https://flatfox.ch/de/listing{short_url}submit/`.
- Displayed ref: `flatfox.ref_format` (default `{ref_house}-{ref_object}`).

## Melon / emonitor v2 (driver `apartments-melon`)

- Flat JSON array. Fields: `title` (ref), `reference_number`, `object_type.key` (apartment,
  attica_apartment, maisonette, studio, penthouse, loft, office …), `floor`, `floor_num`, `rooms`,
  `area`, `balcony_area` / `loggia_area` / `terrace_area`, `rentalgross` (gross), `incidentals`,
  `object_state` (free / reserved / rented), `move_in_date`, `application_form`, `layout_plan`.
- `"#"` means "no file". Commercial units are often **not** in the feed → `commercial-admin`.
- `MELON_MOCK=true` serves `database/data/melon-mock.json` while the endpoint isn't open yet.
- Lead hook (`melon-lead-hook`): POST JSON with Basic Auth to the agency's external-data-hook;
  fields firstname, name, street_nr, postcode, city, email, phone, remarks, howfound=project_website.

## Isometry

The designer delivers an AI file; export and build as in `resources/isometry/README.md` of the
project. Apartment groups must be named like the refs; check the build output's apartment list
against the table.
