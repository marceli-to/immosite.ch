{{-- Accordion: one item open at a time. Items: x-accordion.item. --}}
<div x-data="{ open: null }" {{ $attributes->merge(['class' => 'border-t border-ink']) }}>
  {{ $slot }}
</div>
