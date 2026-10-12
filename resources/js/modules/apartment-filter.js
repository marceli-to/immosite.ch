// Apartment filters (module:apartments): show/hide table rows by their data
// attributes, hide house groups without visible rows, show a hint when nothing matches.

const root = document.querySelector('[data-apartment-filters]');

if (root) {
  const selects = [...root.querySelectorAll('[data-apartment-filter]')];
  const rows = [...document.querySelectorAll('[data-apartment]')];
  const groups = [...document.querySelectorAll('[data-apartment-group]')];
  const empty = document.querySelector('[data-apartment-empty]');

  const apply = () => {
    const active = selects.filter((s) => s.value !== '');

    rows.forEach((row) => {
      row.hidden = !active.every((s) => row.dataset[s.dataset.apartmentFilter] === s.value);
    });

    groups.forEach((group) => {
      group.hidden = !group.querySelector('[data-apartment]:not([hidden])');
    });

    selects.forEach((s) => s.toggleAttribute('data-active', s.value !== ''));

    if (empty) {
      empty.hidden = rows.some((row) => !row.hidden);
    }

    // Lets other modules (isometry) follow the filter.
    document.dispatchEvent(new CustomEvent('apartments:filtered'));
  };

  root.addEventListener('change', apply);

  root.querySelector('[data-apartment-filter-reset]')?.addEventListener('click', () => {
    selects.forEach((s) => (s.value = ''));
    apply();
  });
}
