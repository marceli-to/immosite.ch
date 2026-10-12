@extends('app')
@section('meta_title', 'Lage')
@section('meta_description', 'Lage des Projekts: Einkaufen, Schulen, öffentlicher Verkehr und Naherholung in der Nähe.')

@section('content')

  <x-sections.split>
    <x-headings.h1>Lage</x-headings.h1>
    <p>
      Platzhalter: Quartier und Umgebung in zwei, drei Sätzen. Einkaufen, Schulen, Restaurants und
      Grünflächen in der Nähe.
    </p>
    <p>
      Platzhalter: Anbindung an den öffentlichen Verkehr und ans Strassennetz.
    </p>

    <div class="mt-32 mb-32 lg:mb-48">
      <x-sections.distances />
    </div>

    @if ($url = config('location.google_maps_url'))
      <x-buttons.primary :href="$url" target="_blank" rel="noopener noreferrer">
        Google Maps
        <x-icons.arrow-right class="h-auto w-18 transition-transform group-hover:translate-x-4" />
      </x-buttons.primary>
    @endif

    <x-slot:aside>
      <x-media.picture src="img/placeholder-2" alt="Umgebung" class="aspect-[4/3]" />
    </x-slot:aside>
  </x-sections.split>

  <x-map /> {{-- module:map --}}

@endsection
