{{-- Textabschnitt: Titel + Fliesstext in Lesebreite. --}}
@props(['bg' => 'bg-surface'])

<section class="{{ $bg }}">
  <x-layout.inner {{ $attributes->merge(['class' => 'py-40 md:py-64']) }}>
    <div class="prose-site max-w-text">
      {{ $slot }}
    </div>
  </x-layout.inner>
</section>
