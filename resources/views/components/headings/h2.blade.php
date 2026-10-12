{{-- 40 Desktop → 32 Tablet → 24 Mobile (Faktor 0.6) --}}
<h2 {{ $attributes->merge(['class' => 'mb-20 font-display text-2xl leading-[1.15] font-bold text-balance text-ink md:mb-24 md:text-4xl lg:text-5xl']) }}>
  {{ $slot }}
</h2>
