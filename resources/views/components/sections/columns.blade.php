{{-- Heading + text columns ("Weite. / Ruhe. / Möglichkeit.").
     items: [['title' => 'Weite.', 'text' => '<p>…</p>'], …] (2–4).
     1 column → 2 at sm → all from lg (docs/responsive-rules.md §5). --}}
@props([
  'items' => [],
  'heading' => null,
  'bg' => 'bg-surface',
])

@php
  $columns = [2 => 'lg:grid-cols-2', 3 => 'lg:grid-cols-3', 4 => 'lg:grid-cols-4'][min(max(count($items), 2), 4)];
@endphp

<section class="{{ $bg }}">
  <x-layout.inner {{ $attributes->merge(['class' => 'py-40 md:py-60 lg:py-80']) }}>
    @if ($heading)
      <x-headings.h2 data-reveal>{{ $heading }}</x-headings.h2>
    @endif
    <div class="grid gap-x-40 gap-y-32 sm:grid-cols-2 {{ $columns }}" data-reveal-children>
      @foreach ($items as $item)
        <div class="prose-site">
          <x-headings.h3>{{ $item['title'] }}</x-headings.h3>
          {!! $item['text'] !!}
        </div>
      @endforeach
    </div>
  </x-layout.inner>
</section>
