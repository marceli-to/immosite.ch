@props(['href'])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'text-primary transition-colors hover:text-ink']) }}>{{ $slot }}</a>
