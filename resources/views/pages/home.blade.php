@extends('app')

@php
  // Content of this page; edit here per project.
  $stats = [
    ['number' => 24, 'label' => "moderne\nMietwohnungen"],
    ['number' => 3, 'label' => "Gewerbeflächen\nim Erdgeschoss"],
    ['number' => 30, 'label' => "Einstellplätze\nin der Tiefgarage"],
  ];

  $qualities = [
    ['title' => 'Weite.', 'text' => '<p>Platzhalter: Hohe Räume, grosse Fenster, viel Licht.</p>'],
    ['title' => 'Ruhe.', 'text' => '<p>Platzhalter: Rückzug mitten im Quartier.</p>'],
    ['title' => 'Nähe.', 'text' => '<p>Platzhalter: Einkaufen, Schulen und ÖV in wenigen Minuten.</p>'],
  ];
@endphp

@section('content')

  <x-sections.hero image="img/placeholder-1" alt="Visualisierung des Projekts">
    <x-headings.h1 class="mb-8! text-on-shade md:text-7xl lg:text-8xl">Wohnen an bester Lage</x-headings.h1>
    <p class="text-lg lg:text-2xl">Platzhalter: Ort, Quartier</p>

    <x-slot:stats>
      <x-sections.stats heading="Bis Frühling 2028 entstehen" :items="$stats" />
    </x-slot:stats>
  </x-sections.hero>

  <x-sections.split bleed bg="bg-surface-alt" align="center">
    <x-headings.h2>Platzhalter: Das Projekt</x-headings.h2>
    <p>
      Platzhalter: Hier steht die Einleitung zum Projekt. Lage, Anzahl Wohnungen, Bezugstermin
      und was das Projekt besonders macht, in zwei bis drei Sätzen.
    </p>
    <p>Sobald die Vermietung startet, informieren wir Sie gerne über die verfügbaren Wohnungen.</p>
    <div class="mt-24 md:mt-36">
      <x-buttons.primary href="mailto:{{ config('site.marketer.email') }}">
        Kontakt aufnehmen
        <x-icons.arrow-right class="h-auto w-18 transition-transform group-hover:translate-x-4" />
      </x-buttons.primary>
    </div>

    <x-slot:aside><x-gallery.slider :images="['img/placeholder-2', 'img/placeholder-3', 'img/placeholder-1']" alt="Visualisierung des Projekts" label="Visualisierungen" aspect="aspect-[4/3] md:aspect-auto md:h-full" /></x-slot:aside> {{-- module:gallery --}}
  </x-sections.split>

  <x-sections.columns bg="bg-shade text-on-shade" heading="Platzhalter: Wohnen mit Charakter" :items="$qualities" />

  @include('sections.registration') {{-- module:registration --}}
  @include('sections.contact') {{-- module:contact-form --}}
  <x-map /> {{-- module:map --}}

@endsection
