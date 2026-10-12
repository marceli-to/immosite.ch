@extends('app')
@section('meta_title', 'Gewerbe')
@section('meta_description', 'Gewerbeflächen zur Miete: Flächen, Mietzinse und Grundrisse.')

@section('content')

  <x-sections.text>
    <x-headings.h1>Gewerbe</x-headings.h1>
    <p>Platzhalter: Lage und Eignung der Gewerbeflächen, Ausbaustandard, Bezugstermin.</p>
  </x-sections.text>

  <section id="angebot" class="bg-surface-alt">
    <x-layout.inner class="py-40 md:py-60 lg:py-80">
      @if ($objects->isEmpty())
        <p>Zurzeit sind keine Gewerbeflächen ausgeschrieben.</p>
      @else
        <x-apartments.table :apartments="$objects" :repository="$repository" :columns="config('commercial.columns')" />
      @endif

      @if ($footnote = config('apartments.footnote'))
        <p class="mt-24 text-xs text-muted lg:text-sm">{{ $footnote }}</p>
      @endif
    </x-layout.inner>
  </section>

@endsection
