@props([
  'name',
  'label',
  'id' => null,
  'rows' => 6,
])

@php($id ??= $name)

<div>
  <label for="{{ $id }}" class="sr-only">{{ $label }}</label>
  <textarea
    id="{{ $id }}"
    name="{{ $name }}"
    rows="{{ $rows }}"
    placeholder="{{ $label }}"
    @error($name) aria-invalid="true" aria-describedby="{{ $id }}-error" @enderror
    {{ $attributes->class([
      'w-full resize-y px-16 py-13 text-ink placeholder:text-muted placeholder:opacity-100 outline-none transition focus-visible:ring-2 focus-visible:ring-primary',
      'bg-surface ring-1 ring-field' => ! $errors->has($name),
      'bg-error-bg ring-1 ring-error' => $errors->has($name),
    ]) }}></textarea>
  <x-form.error :name="$name" :id="$id" />
</div>
