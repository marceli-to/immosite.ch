@props([
  'href',
  'title',
  'active' => false,
])

<li>
  <a
    href="{{ $href }}"
    @click="menu = false"
    @if ($active) aria-current="page" @endif
    @class([
      'font-display text-3xl leading-none font-bold uppercase tracking-wide transition-colors hover:text-primary',
      'text-primary' => $active,
      'text-on-header' => ! $active,
    ])>
    {{ $title }}
  </a>
</li>
