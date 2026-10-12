{{-- Image slider (hero or carousel), module:gallery.
     images: list of paths ("img/hero-1") or ['src' => …, 'alt' => …].
     Behaviour per docs/responsive-rules.md §8/§9: the same on all sizes,
     swipe, arrows (≥ 44 px), autoplay stops on interaction. --}}
@props([
  'images' => [],
  'alt' => '',
  'aspect' => 'aspect-[4/3] md:aspect-video lg:aspect-[2/1]',
  'autoplay' => 6000,
  'effect' => 'slide',
  'priority' => false,
  'label' => 'Bildergalerie',
])

@php
  $slides = collect($images)->map(fn ($image) => is_string($image) ? ['src' => $image, 'alt' => $alt] : $image + ['alt' => $alt]);
  $multiple = $slides->count() > 1;
@endphp

<div
  {{ $attributes->merge(['class' => "relative w-full overflow-hidden bg-surface-alt {$aspect}"]) }}
  role="region"
  aria-roledescription="carousel"
  aria-label="{{ $label }}">
  <div class="swiper h-full" @if ($multiple) data-gallery='@json(['autoplay' => $autoplay, 'effect' => $effect])' @endif>
    <div class="swiper-wrapper">
      @foreach ($slides as $slide)
        <div class="swiper-slide h-full" role="group" aria-roledescription="slide" aria-label="{{ $loop->iteration }} von {{ $slides->count() }}">
          <x-media.picture :src="$slide['src']" :alt="$slide['alt']" :priority="$priority && $loop->first" class="h-full" />
        </div>
      @endforeach
    </div>
  </div>

  @if ($multiple)
    <button type="button" data-gallery-prev aria-label="Vorheriges Bild"
      class="absolute top-1/2 left-8 z-10 flex size-44 -translate-y-1/2 cursor-pointer items-center justify-center text-white drop-shadow transition-opacity hover:opacity-70 md:left-16">
      <x-icons.chevron-right class="h-auto w-20 rotate-180 md:w-24" />
    </button>
    <button type="button" data-gallery-next aria-label="Nächstes Bild"
      class="absolute top-1/2 right-8 z-10 flex size-44 -translate-y-1/2 cursor-pointer items-center justify-center text-white drop-shadow transition-opacity hover:opacity-70 md:right-16">
      <x-icons.chevron-right class="h-auto w-20 md:w-24" />
    </button>
    <div data-gallery-pagination class="absolute inset-x-0 bottom-12 z-10 flex justify-center gap-8 md:bottom-16"></div>
  @endif
</div>
