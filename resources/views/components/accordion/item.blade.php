{{-- One accordion entry. Button with aria-expanded/aria-controls for screen
     readers; animated with Alpine collapse (registered in resources/js/app.js). --}}
@props(['title'])

@php($id = 'accordion-'.\Illuminate\Support\Str::random(8))

<div class="border-b border-ink">
  <h3>
    <button type="button"
      id="{{ $id }}-button"
      aria-controls="{{ $id }}-panel"
      :aria-expanded="open === '{{ $id }}'"
      @click="open = open === '{{ $id }}' ? null : '{{ $id }}'"
      class="flex w-full cursor-pointer items-center justify-between gap-16 py-16 text-left font-display text-lg font-bold transition-colors hover:text-primary lg:py-20 lg:text-xl">
      <span>{{ $title }}</span>
      <x-icons.chevron-down class="h-auto w-16 shrink-0 transition-transform duration-300 lg:w-18" ::class="open === '{{ $id }}' && 'rotate-180'" />
    </button>
  </h3>
  <div id="{{ $id }}-panel" role="region" aria-labelledby="{{ $id }}-button" x-show="open === '{{ $id }}'" x-collapse x-cloak>
    <div class="prose-site pb-20 lg:pb-28">
      {{ $slot }}
    </div>
  </div>
</div>
