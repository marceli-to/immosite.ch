@extends('app')
@section('meta_title', 'Muster')

{{-- Pattern library (local only, routes/web.php): every file in resources/views/patterns
     except this one is one pattern, shown in file-name order. Modules bring their own
     pattern files, so the page always shows exactly what this project has. --}}
@push('head')
  <meta name="robots" content="noindex, nofollow">
@endpush

@php
  $patterns = collect(glob(resource_path('views/patterns/*.blade.php')))
    ->map(fn ($file) => basename($file, '.blade.php'))
    ->reject(fn ($name) => $name === 'index')
    ->sort()
    ->values();
@endphp

@section('content')
  <x-sections.text>
    <x-headings.h1>Muster</x-headings.h1>
    <p>Alle Bausteine dieses Projekts mit Beispielinhalt. Nur lokal sichtbar.</p>
    <ul>
      @foreach ($patterns as $name)
        <li><a href="#muster-{{ $name }}">{{ $name }}</a></li>
      @endforeach
    </ul>
  </x-sections.text>

  @foreach ($patterns as $name)
    <div id="muster-{{ $name }}" class="border-t-4 border-primary">
      <x-layout.inner class="py-12 font-mono text-xs text-muted">resources/views/patterns/{{ $name }}.blade.php</x-layout.inner>
      @include("patterns.{$name}")
    </div>
  @endforeach
@endsection
