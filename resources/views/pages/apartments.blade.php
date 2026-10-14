@extends('app')
@section('meta_title', 'Wohnungen')
@section('meta_description', 'Verfügbare Mietwohnungen: Zimmer, Flächen, Mietzinse und Grundrisse.')

@section('content')

  <x-sections.text>
    <x-headings.h1>Wohnungen</x-headings.h1>
    <p>Platzhalter: Kurze Einleitung zum Angebot, Bezugstermin, Ansprechperson.</p>
  </x-sections.text>

  <section class="bg-surface-alt">
    <x-layout.inner class="py-40 md:py-60 lg:py-80">
      <x-apartments.filter :filters="$filters" />

      {{-- Optional drawing next to the tables (module isometry); without it the tables take the full width. --}}
      <div class="lg:flex lg:items-start lg:gap-x-40">
        @include('isometry.aside') {{-- module:isometry --}}

        <div class="min-w-0 space-y-40 lg:flex-1 lg:space-y-64">
          @foreach ($groups as $heading => $apartments)
            <div data-apartment-group>
              @if ($heading !== '')
                <x-headings.h2>{{ $heading }}</x-headings.h2>
              @endif
              <x-apartments.table :apartments="$apartments" :repository="$repository" />
            </div>
          @endforeach
        </div>
      </div>

      <x-apartments.extras class="mt-40 lg:mt-64" />

      <p data-apartment-empty hidden class="py-24">Keine Wohnungen gefunden. Bitte passen Sie die Filter an.</p>

      @if ($footnote = config('apartments.footnote'))
        <p class="mt-24 text-xs text-muted lg:text-sm">{{ $footnote }}</p>
      @endif

      <x-downloads.buttons group="apartments" class="mt-32 lg:mt-48" /> {{-- module:downloads --}}
    </x-layout.inner>
  </section>

@endsection
