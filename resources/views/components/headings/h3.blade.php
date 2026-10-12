{{-- 24 desktop → 20 mobile (factor 0.9) --}}
<h3 {{ $attributes->merge(['class' => 'mb-8 font-display text-xl leading-[1.3] font-bold lg:text-2xl']) }}>
  {{ $slot }}
</h3>
