{{-- Text section: title + body text at reading width.
     Spacing 80 desktop → 60 tablet → 40 mobile (factor 0.5, §3). --}}
@props(['bg' => 'bg-surface'])

<section class="{{ $bg }}">
  <x-layout.inner {{ $attributes->merge(['class' => 'py-40 md:py-60 lg:py-80']) }}>
    <div class="prose-site max-w-text" data-reveal>
      {{ $slot }}
    </div>
  </x-layout.inner>
</section>
