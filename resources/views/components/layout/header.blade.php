@php
  $nav = collect(config('site.pages'))->where('nav', true);
@endphp

<header class="sticky top-0 z-50 w-full bg-surface-alt">
  <x-layout.inner class="flex items-center justify-between gap-x-40 py-20 md:py-28">

    <a href="{{ route('page.home') }}" aria-label="{{ config('site.name') }} – Startseite" class="relative z-70 shrink-0">
      <x-icons.logo class="block h-auto w-140 md:w-180" />
    </a>

    {{-- Eine Seite allein braucht keine Navigation (Landingpage). --}}
    @if ($nav->count() > 1)
      <x-menu.desktop.wrapper :items="$nav" class="hidden md:block" />
      <x-menu.mobile.button class="md:hidden" />
    @endif

  </x-layout.inner>

  @if ($nav->count() > 1)
    <x-menu.mobile.wrapper :items="$nav" />
  @endif
</header>
