{{-- Text + Bildspalte. Mobile: Text zuerst, Bild darunter (DOM-Reihenfolge,
     docs/responsive-rules.md §5). Ab md zwei Spalten, Verhaeltnis per $ratio.
     Abstaende: Abschnitt 40 → 60 → 80 (§3). --}}
@props([
  'bg' => 'bg-surface',
  'ratio' => '7/5',
  'align' => 'start',
])

@php
  $columns = [
    '1/1' => ['md:col-span-6', 'md:col-span-6'],
    '7/5' => ['md:col-span-7', 'md:col-span-5'],
    '5/7' => ['md:col-span-5', 'md:col-span-7'],
    '8/4' => ['md:col-span-8', 'md:col-span-4'],
  ][$ratio];
@endphp

<section class="{{ $bg }}">
  <x-layout.inner {{ $attributes->merge(['class' => 'py-40 md:py-60 lg:py-80']) }}>
    <div @class([
      'grid grid-cols-1 gap-y-32 md:grid-cols-12 md:gap-x-20 lg:gap-x-40',
      'md:items-center' => $align === 'center',
    ])>
      {{-- min-w-0: sonst dehnt breiter Inhalt (Tabellen) die Spalte statt selbst zu scrollen. --}}
      <div class="prose-site min-w-0 {{ $columns[0] }}">
        {{ $slot }}
      </div>
      @isset($aside)
        <div class="min-w-0 {{ $columns[1] }}">
          {{ $aside }}
        </div>
      @endisset
    </div>
  </x-layout.inner>
</section>
