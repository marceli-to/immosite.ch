@props([
  'name',
  'label',
  'type' => 'text',
])

<div>
  <label for="{{ $name }}" class="sr-only">{{ $label }}</label>
  <input
    type="{{ $type }}"
    id="{{ $name }}"
    name="{{ $name }}"
    placeholder="{{ $label }}"
    @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
    {{ $attributes->class([
      'w-full px-16 py-13 text-ink placeholder:text-muted placeholder:opacity-100 outline-none transition focus-visible:ring-2 focus-visible:ring-primary',
      'bg-surface ring-1 ring-line' => ! $errors->has($name),
      'bg-error-bg ring-1 ring-error' => $errors->has($name),
    ]) }}>
  <x-form.error :name="$name" />
</div>
