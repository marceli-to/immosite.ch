{{-- Bild mit modernen Formaten und korrekten Abmessungen.
     src: Pfad unter public/ ohne Endung, z.B. "img/hero-1" → hero-1.jpg (Pflicht),
     hero-1.avif / .webp und hero-1-mobile.* (bis 767 px) werden genutzt, wenn vorhanden.
     width/height kommen aus der JPG-Datei, damit die Seite beim Laden nicht springt
     (Adler/Scala hatten Hochformat-Werte auf Querformat-Bildern). --}}
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

<picture>
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
