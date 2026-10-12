@props([
  'href',
  'title',
  'active' => false,
])

<li>
  <a
    href="{{ $href }}"
    @if ($active) aria-current="page" @endif
    @class([
      'font-display text-xl leading-none font-bold uppercase tracking-wide transition-colors hover:text-primary',
      'text-primary' => $active,
      'text-ink' => ! $active,
    ])>
    {{ $title }}
  </a>
</li>
