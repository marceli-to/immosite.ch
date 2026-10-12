@php
  $nav = collect(config('site.pages'))->where('nav', true);

  // Burger below md, or below lg/xl when the nav doesn't fit
  // (docs/responsive-rules.md §6). null = decide by the number of entries.
  $breakpoint = config('site.nav_breakpoint') ?? match (true) {
      $nav->count() <= 4 => 'md',
      $nav->count() <= 6 => 'lg',
      default => 'xl',
  };
  // Full class names so Tailwind finds them.
  [$showDesktop, $hideOnDesktop] = [
      'md' => ['hidden md:block', 'md:hidden'],
      'lg' => ['hidden lg:block', 'lg:hidden'],
      'xl' => ['hidden xl:block', 'xl:hidden'],
  ][$breakpoint];
@endphp

<header class="sticky top-0 z-50 w-full bg-header text-on-header">
  {{-- Fixed height (--header-height in app.css): sticky elements and anchor
       offsets below the header use the same value. --}}
  <x-layout.inner class="flex h-(--header-height) items-center justify-between gap-x-40">

    <a href="{{ route('page.home') }}" aria-label="{{ config('site.name') }} – Startseite" class="relative z-70 shrink-0">
      <x-icons.logo class="block h-32 w-auto lg:h-40" />
    </a>

    {{-- A single page needs no navigation (landing page). --}}
    @if ($nav->count() > 1)
      <x-menu.desktop.wrapper :items="$nav" :class="$showDesktop" />
      <x-menu.mobile.button :class="$hideOnDesktop" />
    @endif

  </x-layout.inner>

  @if ($nav->count() > 1)
    <x-menu.mobile.wrapper :items="$nav" :class="$hideOnDesktop" />
  @endif
</header>
