{{-- Textabschnitt: Titel + Fliesstext in Lesebreite.
     Abstand 80 Desktop → 60 Tablet → 40 Mobile (Faktor 0.5, §3). --}}
@props(['bg' => 'bg-surface'])

<section class="{{ $bg }}">
  <x-layout.inner {{ $attributes->merge(['class' => 'py-40 md:py-60 lg:py-80']) }}>
    <div class="prose-site max-w-text">
      {{ $slot }}
    </div>
  </x-layout.inner>
</section>
