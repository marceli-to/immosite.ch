{{-- Honeypot: invisible to humans, bots fill it in. Not display:none,
     because some bots skip such fields; positioned off-screen instead. --}}
@props(['id' => 'website'])

<div aria-hidden="true" class="absolute -left-[9999px] h-1 w-1 overflow-hidden">
  <label for="{{ $id }}">Website</label>
  <input type="text" id="{{ $id }}" name="website" tabindex="-1" autocomplete="off" {{ $attributes }}>
</div>
