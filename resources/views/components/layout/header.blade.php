@php
  $nav = collect(config('site.pages'))->where('nav', true);

  // Burger below md, or below lg when the nav doesn't fit at 768 px
  // (docs/responsive-rules.md §6). null = decide by the number of entries.
  $breakpoint = config('site.nav_breakpoint') ?? ($nav->count() > 4 ? 'lg' : 'md');
  // Full class names so Tailwind finds them.
  [$showDesktop, $hideOnDesktop] = $breakpoint === 'lg' ? ['hidden lg:block', 'lg:hidden'] : ['hidden md:block', 'md:hidden'];
@endphp

<header class="sticky top-0 z-50 w-full bg-surface-alt">
  <x-layout.inner class="flex items-center justify-between gap-x-40 py-20 lg:py-28">

    <a href="{{ route('page.home') }}" aria-label="{{ config('site.name') }} – Startseite" class="relative z-70 shrink-0">
      <x-icons.logo class="block h-auto w-140 lg:w-180" />
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
