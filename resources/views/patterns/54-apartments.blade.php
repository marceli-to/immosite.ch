{{-- x-apartments.table + x-apartments.status (module:apartments): columns from config/apartments.php --}}
@php
  $demo = collect([
    ['ref' => 'A.01', 'floor' => 'EG', 'rooms' => 2.5, 'area' => 61, 'rent_gross' => 2070, 'status' => 'free'],
    ['ref' => 'A.02', 'floor' => '1', 'rooms' => 3.5, 'area' => 84, 'rent_gross' => 2670, 'status' => 'reserved'],
    ['ref' => 'A.03', 'floor' => '2', 'rooms' => 4.5, 'area' => 108, 'rent_gross' => 3360, 'status' => 'rented'],
  ])->map(fn ($row) => \App\Apartments\Apartment::fromArray($row));
@endphp
<x-sections.text>
  <x-headings.h2>Wohnungstabelle</x-headings.h2>
</x-sections.text>
<x-layout.inner class="pb-40 lg:pb-80">
  <x-apartments.table :apartments="$demo" :repository="app(\App\Apartments\ApartmentRepository::class)" />
</x-layout.inner>
