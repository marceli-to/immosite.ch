@php
  $marketer = config('site.marketer');
  $design = config('site.credits.design');
  $legal = collect(config('site.pages'))->where('footer', true);
@endphp

<footer>

  <x-layout.inner class="py-32 md:py-48">
    <div class="grid gap-x-40 gap-y-24 text-small sm:grid-cols-2 md:grid-cols-3">

      <div>
        <p class="font-bold">{{ $marketer['name'] }}</p>
        <p>{{ $marketer['street'] }}<br>{{ $marketer['city'] }}</p>
      </div>

      <div>
        <p class="font-bold">Kontakt</p>
        <p>
          {{ $marketer['contact'] }}<br>
          {{ $marketer['role'] }}<br>
          <a href="tel:{{ preg_replace('/\s+/', '', $marketer['phone']) }}" class="transition-colors hover:text-primary">{{ $marketer['phone'] }}</a><br>
          <a href="mailto:{{ $marketer['email'] }}" class="transition-colors hover:text-primary">{{ $marketer['email'] }}</a>
        </p>
      </div>

    </div>
  </x-layout.inner>

  <div class="bg-surface-alt">
    <x-layout.inner class="flex flex-wrap gap-x-28 gap-y-6 py-12 text-small">
      @foreach ($legal as $page)
        <a href="{{ route($page['route']) }}" class="transition-colors hover:text-primary">{{ $page['title'] }}</a>
      @endforeach
      @if ($design)
        <a href="{{ $design['url'] }}" target="_blank" rel="noopener noreferrer" class="transition-colors hover:text-primary">{{ $design['label'] }}</a>
      @endif
    </x-layout.inner>
  </div>

</footer>
