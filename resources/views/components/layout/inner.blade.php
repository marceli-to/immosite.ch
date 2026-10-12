{{-- Side padding = --gutter (20 → 30 → design margin 48), docs/responsive-rules.md §4 --}}
<div {{ $attributes->merge(['class' => 'mx-auto w-full max-w-site px-(--gutter)']) }}>
  {{ $slot }}
</div>
