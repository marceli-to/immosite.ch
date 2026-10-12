{{-- Placeholder: replaced per project by the logo (SVG). The viewBox grows with the
     name so long project names aren't clipped; the header sizes it by height. --}}
@php
  $name = config('site.name');
  $width = 52 + (int) ceil(mb_strlen($name) * 13.5);
@endphp
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {{ $width }} 40" role="img" aria-label="{{ $name }}" {{ $attributes }}>
  <rect x="0" y="0" width="40" height="40" fill="currentColor" class="text-primary" />
  <text x="52" y="28" font-family="ui-sans-serif, system-ui, sans-serif" font-size="22" font-weight="700" fill="currentColor">{{ $name }}</text>
</svg>
