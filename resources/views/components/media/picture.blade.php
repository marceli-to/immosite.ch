{{-- Image with modern formats and correct dimensions.
     src: path under public/ without extension, e.g. "img/hero-1" → hero-1.jpg (required),
     hero-1.avif / .webp and hero-1-mobile.* (up to 767 px) are used if present.
     width/height come from the JPG file, so the page doesn't jump while loading
     (Adler/Scala had portrait values on landscape images). --}}
@props([
  'src',
  'alt' => '',
  'priority' => false,
])

@php
  $exists = fn (string $path) => file_exists(public_path($path));
  [$width, $height] = $exists("{$src}.jpg") ? getimagesize(public_path("{$src}.jpg")) : [null, null];
  $variants = [];

  foreach (['-mobile' => '(max-width: 767px)', '' => null] as $suffix => $media) {
    foreach (['avif' => 'image/avif', 'webp' => 'image/webp', 'jpg' => 'image/jpeg'] as $ext => $type) {
      if ($exists("{$src}{$suffix}.{$ext}") && ($suffix !== '' || $ext !== 'jpg')) {
        $variants[] = ['srcset' => asset("{$src}{$suffix}.{$ext}"), 'type' => $type, 'media' => $media];
      }
    }
  }
@endphp

<picture class="block">
  @foreach ($variants as $variant)
    <source srcset="{{ $variant['srcset'] }}" type="{{ $variant['type'] }}"@if ($variant['media']) media="{{ $variant['media'] }}"@endif>
  @endforeach
  <img
    src="{{ asset("{$src}.jpg") }}"
    alt="{{ $alt }}"
    @if ($width) width="{{ $width }}" height="{{ $height }}" @endif
    @if ($priority) fetchpriority="high" @else loading="lazy" decoding="async" @endif
    {{ $attributes->merge(['class' => 'block w-full object-cover']) }}>
</picture>
