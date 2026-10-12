@props(['name', 'id' => null])

@error($name)
  <p id="{{ $id ?? $name }}-error" class="mt-6 text-xs text-error lg:text-sm">{{ $message }}</p>
@enderror
