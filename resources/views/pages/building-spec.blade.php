@extends('app')
@section('meta_title', 'Ausbau')
@section('meta_description', 'Ausbaustandard der Wohnungen: Böden, Küche, Bad, Technik.')

@php
  $sections = config('building-spec.sections');
@endphp

@section('content')

  <x-sections.text>
    <x-headings.h1>Ausbau</x-headings.h1>

    @if (config('building-spec.display') === 'accordion')
      <x-accordion.group class="mt-32 lg:mt-48">
        @foreach ($sections as $title => $lines)
          <x-accordion.item :title="$title">
            <p>{!! implode('<br>', array_map('e', $lines)) !!}</p>
          </x-accordion.item>
        @endforeach
      </x-accordion.group>
    @else
      @foreach ($sections as $title => $lines)
        <x-headings.h3 class="mt-24 lg:mt-32">{{ $title }}</x-headings.h3>
        <p>{!! implode('<br>', array_map('e', $lines)) !!}</p>
      @endforeach
    @endif

    <x-downloads.buttons group="building-spec" class="mt-32 lg:mt-48" /> {{-- module:downloads --}}
  </x-sections.text>

@endsection
