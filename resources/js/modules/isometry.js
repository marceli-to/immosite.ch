// Isometry (module:isometry): links the building drawing with the apartment table.
// Row hover/focus → lift the floors above and mark the apartment; hover over a floor
// lifts the ones above it (only then are its apartments reachable); hover/tap on an
// apartment marks its row, tap also scrolls the row into view and opens its details
// (touch has no hover).

const svg = document.querySelector('[data-isometry]');

if (svg) {
  const rows = new Map([...document.querySelectorAll('[data-apartment]')].map((row) => [row.dataset.apartment, row]));
  const floors = [...svg.querySelectorAll('[data-iso-floor]')]; // bottom to top in DOM order
  const parts = [...svg.querySelectorAll('[data-iso]')];

  // Status colours come from the table, so they follow the API data.
  parts.forEach((part) => {
    const row = rows.get(part.dataset.iso);
    if (row) part.dataset.status = row.dataset.status;
  });

  const clear = () => {
    floors.forEach((f) => f.classList.remove('is-up', 'is-active'));
    svg.querySelectorAll('.is-selected').forEach((el) => el.classList.remove('is-selected'));
    rows.forEach((row) => row.classList.remove('is-selected'));
  };

  const liftAbove = (floor) => {
    floors.slice(floors.indexOf(floor) + 1).forEach((f) => f.classList.add('is-up'));
  };

  const partFor = (ref) => parts.find((p) => p.dataset.iso === ref);

  const select = (ref) => {
    clear();
    const row = rows.get(ref);
    const part = partFor(ref);
    const floor = part?.closest('[data-iso-floor]') ?? floors.find((f) => f.dataset.isoFloor === row?.dataset.floor);

    if (floor) liftAbove(floor);
    if (part) part.classList.add('is-selected');
    else floor?.classList.add('is-active');
    row?.classList.add('is-selected');
  };

  rows.forEach((row, ref) => {
    row.addEventListener('mouseenter', () => select(ref));
    row.addEventListener('mouseleave', clear);
    row.addEventListener('focusin', () => select(ref));
    row.addEventListener('focusout', clear);
  });

  let current;
  svg.addEventListener('mouseover', (e) => {
    const part = e.target.closest('[data-iso]');
    const floor = e.target.closest('[data-iso-floor]');
    const key = part ? `part:${part.dataset.iso}` : floor ? `floor:${floor.dataset.isoFloor}` : null;
    if (key === current) return;
    current = key;

    if (part) {
      select(part.dataset.iso);
    } else if (floor) {
      clear();
      liftAbove(floor);
    }
  });

  svg.addEventListener('mouseleave', () => {
    current = undefined;
    clear();
  });

  svg.addEventListener('click', (e) => {
    const part = e.target.closest('[data-iso]');
    if (!part) return;
    select(part.dataset.iso);
    const row = rows.get(part.dataset.iso);
    // Mobile list: open the apartment's details (table.blade.php listens for it).
    row?.dispatchEvent(new CustomEvent('apartment-open'));
    row?.scrollIntoView({ behavior: 'smooth', block: 'center' });
  });

  // Mobile list: opening an apartment's details marks it in the drawing (touch has no hover).
  document.addEventListener('apartments:toggle', (e) => (e.detail.open ? select(e.detail.ref) : clear()));

  // Dim apartments the table filters have hidden.
  document.addEventListener('apartments:filtered', () => {
    parts.forEach((part) => part.classList.toggle('is-dimmed', rows.get(part.dataset.iso)?.hidden ?? false));
  });
}
