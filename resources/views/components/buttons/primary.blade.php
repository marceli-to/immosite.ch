@props([
  'href' => null,
  'type' => 'button',
])

@php
  $classes = 'group inline-flex cursor-pointer items-center gap-10 rounded-[3px] bg-primary px-16 pt-10 pb-12 font-display text-lg font-bold leading-none text-on-primary transition-colors hover:bg-ink lg:text-xl';
@endphp

@if ($href)
  <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
  <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
