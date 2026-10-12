{{-- Full-width map. Height per docs/responsive-rules.md §10:
     1:1 mobile, 16:9 tablet, design ratio from lg (default 3:1). --}}
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
    {{-- h-full instead of absolute inset-0: Mapbox sets position: relative on the
         container, so the area would otherwise collapse to 0 px. --}}
    class="h-full w-full opacity-0 transition-opacity duration-700"></div>
</div>
