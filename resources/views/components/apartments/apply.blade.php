{{-- "Bewerben" button in the apartment table (module:apply). --}}
@props(['apartment'])

@php
  $mode = config('apply.mode');
  $external = $mode !== 'form' && $apartment->applyUrl;
  $href = $external ? $apartment->applyUrl : ($mode === 'link' ? null : route('page.apply', ['wohnung' => $apartment->ref]));
@endphp

@if ($apartment->isAvailable() && $href)
  <a href="{{ $href }}" @if ($external) target="_blank" rel="noopener" @endif
    aria-label="{{ config('apply.label') }}: Wohnung {{ $apartment->ref }}"
    class="inline-flex items-center rounded-[3px] bg-primary px-10 pt-6 pb-7 text-xs leading-none font-bold whitespace-nowrap text-on-primary transition-colors hover:bg-ink lg:text-sm">
    {{ config('apply.label') }}
  </a>
@endif
