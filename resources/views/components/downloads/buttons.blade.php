{{-- PDF buttons of one group from config/downloads.php. --}}
@props(['group'])

@php
  $files = config("downloads.groups.{$group}", []);
@endphp

@if ($files)
  <div {{ $attributes->merge(['class' => 'flex flex-wrap gap-12 lg:gap-16']) }}>
    @foreach ($files as [$label, $file])
      @if (file_exists(public_path($file)))
        <x-dynamic-component :component="'buttons.'.config('downloads.button', 'secondary')" :href="asset($file)" target="_blank" rel="noopener">
          <x-icons.download class="size-16" />
          {{ $label }}
        </x-dynamic-component>
      @else
        <span class="inline-flex cursor-not-allowed items-center gap-10 rounded-[3px] border border-line px-16 pt-9 pb-11 font-display text-lg leading-none font-bold text-muted lg:text-xl" aria-disabled="true">
          <x-icons.download class="size-16" />
          {{ $label }} <span class="font-normal">(PDF folgt)</span>
        </span>
      @endif
    @endforeach
  </div>
@endif
