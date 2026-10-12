{{-- Side margins 20 → 30 → design margin (48) from lg, docs/responsive-rules.md §4 --}}
<div {{ $attributes->merge(['class' => 'mx-auto w-full max-w-site px-20 md:px-30 lg:px-48']) }}>
  {{ $slot }}
</div>
