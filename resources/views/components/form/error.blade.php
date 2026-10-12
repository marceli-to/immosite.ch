@props(['name'])

@error($name)
  <p id="{{ $name }}-error" class="mt-6 text-xs text-error lg:text-sm">{{ $message }}</p>
@enderror
