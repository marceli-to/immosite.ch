@extends('app')

@section('content')

  <section class="bg-surface-alt">
    <x-layout.inner class="py-48 md:py-72 lg:py-96">
      <div class="prose-site max-w-text">
        <x-headings.h1>Wohnen an bester Lage</x-headings.h1>
        <p>
          Platzhalter: Hier steht die Einleitung zum Projekt. Lage, Anzahl Wohnungen, Bezugstermin
          und was das Projekt besonders macht, in zwei bis drei Sätzen.
        </p>
        <p>
          Sobald die Vermietung startet, informieren wir Sie gerne über die verfügbaren Wohnungen.
        </p>
      </div>
      <div class="mt-24 md:mt-36 lg:mt-48">
        <x-buttons.primary href="mailto:{{ config('site.marketer.email') }}">
          Kontakt aufnehmen
          <x-icons.arrow-right class="h-auto w-18 transition-transform group-hover:translate-x-4" />
        </x-buttons.primary>
      </div>
    </x-layout.inner>
  </section>

  @include('sections.registration') {{-- module:registration --}}

@endsection
