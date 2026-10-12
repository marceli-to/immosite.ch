# Isometry

Interactive building drawing next to the apartment table (module `isometry`).

- `source.svg`: the designer's Illustrator SVG export (the starter has a generated demo).
- `building.svg`: built from it with `php artisan isometry:build`. Don't edit by hand.
- Settings: `config/isometry.php`. Behaviour: `resources/js/modules/isometry.js`, colours:
  `resources/css/isometry.css` and the `--color-iso-*` tokens in `app.css`.

## What the AI file needs

1. **One top-level layer per floor**, bottom to top, named like the keys in
   `isometry.floors` (e.g. `EG`, `1. OG`). Their values are the apartment floor values
   (`Apartment::$floor`, the keys of `apartments.floors`).
2. **Optional layer `Umgebung`** for ground, trees etc. It stays still.
3. **One group (or shape) per apartment inside its floor, named like the apartment ref**
   (`A.01`) or without the dot (`V101`); `isometry.ref_pattern` / `ref_format` turn the
   name into the ref (default: `V101` → `V.101`). Other groups (Gang, Lift, Treppe) stay
   passive.
4. **Side faces and top faces in two fixed colours** (`isometry.face_fill`,
   `isometry.top_fill`, default `#d0dde0` / `#e0e9eb`). The build marks these shapes so
   the site can colour them by status.
5. Avoid transparency, effects and outlined text; floor labels as live text if any.

## Export and build

Illustrator → *Export → Export As… → SVG*: Styling "Internal CSS", Object IDs "Layer
Names", Decimal 1, Minify off, Responsive on. Save as `resources/isometry/source.svg`, then:

```bash
php artisan isometry:build   # prints floors and apartments it found
npm run build
```

Then check `isometry.lift` (how far upper floors rise, in SVG units, about two floor
heights) and compare the apartments it found with the table.
