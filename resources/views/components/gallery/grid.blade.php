{{-- Image grid with full-screen lightbox, module:gallery.
     images: list of paths ("img/haus-1") or ['src' => …, 'alt' => …].
     group: images of the same group are browsed together in the lightbox; several
     grids on one page need different groups. Without JS each tile links to the JPG. --}}
@props([
  'images' => [],
  'alt' => '',
  'group' => 'galerie',
  'cols' => 'grid-cols-2 md:grid-cols-3 lg:grid-cols-4',
  'aspect' => 'aspect-[4/3]',
])

@php
  $tiles = collect($images)->map(fn ($image) => is_string($image) ? ['src' => $image, 'alt' => $alt] : $image + ['alt' => $alt]);
@endphp

<div {{ $attributes->merge(['class' => "grid gap-12 md:gap-16 lg:gap-24 {$cols}"]) }}>
  @foreach ($tiles as $tile)
    <a
      href="{{ asset($tile['src'].'.jpg') }}"
      data-lightbox="{{ $group }}"
      aria-label="{{ $tile['alt'] ? $tile['alt'].' – ' : '' }}Bild {{ $loop->iteration }} von {{ $tiles->count() }} vergrössern"
      class="group relative block overflow-hidden bg-surface-alt {{ $aspect }}">
      <x-media.picture :src="$tile['src']" :alt="$tile['alt']" class="h-full transition-transform duration-500 group-hover:scale-105 motion-reduce:transition-none" />
    </a>
  @endforeach
</div>
