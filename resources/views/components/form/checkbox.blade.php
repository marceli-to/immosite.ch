@props([
  'id',
  'error' => false,
  'multiline' => false,
])

<label for="{{ $id }}" @class([
  'flex cursor-pointer gap-12',
  'items-center' => ! $multiline,
  'items-start' => $multiline,
])>
  @if ($multiline)
    <span class="flex h-lh items-center">
  @endif
  <span class="group inline-grid size-20 shrink-0 grid-cols-1">
    <input
      id="{{ $id }}"
      type="checkbox"
      {{ $attributes->class([
        'col-start-1 row-start-1 appearance-none rounded-none border forced-colors:appearance-auto',
        'border-field bg-surface' => ! $error,
        'border-error bg-error-bg' => $error,
      ]) }}>
    <svg viewBox="0 0 14 14" fill="none" aria-hidden="true" class="pointer-events-none col-start-1 row-start-1 size-7/8 self-center justify-self-center stroke-primary">
      <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-not-has-checked:opacity-0" />
    </svg>
  </span>
  @if ($multiline)
    </span>
  @endif
  <span>{{ $slot }}</span>
</label>
