{{-- Karte ueber die volle Breite. Hoehe nach docs/responsive-rules.md §10:
     1:1 Mobile, 16:9 Tablet, Designverhaeltnis ab lg (Standard 3:1). --}}
@props([
  'aspect' => 'aspect-square md:aspect-video lg:aspect-[3/1]',
])

@php
  $config = [
    'token' => config('map.token'),
    'style' => config('map.style'),
    'center' => config('map.center'),
    'zoom' => config('map.zoom'),
    'markers' => config('map.markers'),
  ];
@endphp

<div {{ $attributes->merge(['class' => "w-full bg-surface-alt {$aspect}"]) }}>
  <div
    data-map='@json($config)'
    role="region"
    aria-label="Karte"
    {{-- h-full statt absolute inset-0: Mapbox setzt auf den Container
         position: relative, die Flaeche fiele sonst auf 0 px zusammen. --}}
    class="h-full w-full opacity-0 transition-opacity duration-700"></div>
</div>
