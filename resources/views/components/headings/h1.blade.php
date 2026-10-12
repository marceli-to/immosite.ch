{{-- 48 Desktop → 40 Tablet → 28 Mobile (Faktor 0.6, docs/responsive-rules.md §2) --}}
<h1 {{ $attributes->merge(['class' => 'mb-24 font-display text-3xl leading-[1.1] font-bold text-balance text-ink md:mb-32 md:text-5xl lg:text-6xl']) }}>
  {{ $slot }}
</h1>
