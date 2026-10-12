@props([
  'href' => null,
  'type' => 'button',
])

@php
  $classes = 'group inline-flex cursor-pointer items-center gap-10 rounded-[3px] border border-primary px-16 pt-9 pb-11 font-display text-lg font-bold leading-none text-primary transition-colors hover:bg-primary hover:text-on-primary lg:text-xl';
@endphp

@if ($href)
  <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
  <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
