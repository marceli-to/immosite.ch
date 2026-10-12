{{-- Hero: image (or a slider in the media slot) with a gradient, the title over the
     image and optional key figures. Figures sit on the image from md; on mobile below
     it on a shade band, where there is room for them.
     Height: fullscreen = window minus header, otherwise by aspect ratio (§8). --}}
@props([
  'image' => null,
  'alt' => '',
  'fullscreen' => true,
  'aspect' => 'aspect-[4/3] md:aspect-video lg:aspect-[2/1]',
])

<section class="relative">
  <div @class([
    'relative overflow-hidden bg-surface-alt',
    'h-[calc(100svh-var(--header-height))] min-h-[420px]' => $fullscreen,
    $aspect => ! $fullscreen,
  ])>
    @isset($media)
      {{ $media }}
    @elseif ($image)
      <x-media.picture :src="$image" :alt="$alt" :priority="true" class="h-full" />
    @endisset

    <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 bottom-0 h-2/3 bg-linear-to-b from-transparent to-shade/70"></div>

    <x-layout.inner class="absolute inset-x-0 bottom-0 z-10 pb-32 text-on-shade md:pb-48 lg:pb-64">
      <div data-reveal>
        {{ $slot }}
      </div>
      @isset($stats)
        <div class="mt-40 hidden md:block lg:mt-64">{{ $stats }}</div>
      @endisset
    </x-layout.inner>
  </div>

  @isset($stats)
    <div class="bg-shade text-on-shade md:hidden">
      <x-layout.inner class="py-32">{{ $stats }}</x-layout.inner>
    </div>
  @endisset
</section>
