{{-- x-sections.columns: heading + 2–4 text columns, any bg (e.g. bg-shade text-on-shade) --}}
<x-sections.columns bg="bg-shade text-on-shade" heading="Spalten" :items="[
  ['title' => 'Weite.', 'text' => '<p>Erste Spalte.</p>'],
  ['title' => 'Ruhe.', 'text' => '<p>Zweite Spalte.</p>'],
  ['title' => 'Nähe.', 'text' => '<p>Dritte Spalte.</p>'],
]" />
