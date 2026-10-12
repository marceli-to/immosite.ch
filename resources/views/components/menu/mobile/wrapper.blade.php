@props(['items'])

<div
  x-cloak
  x-show="menu"
  x-transition.opacity.duration.200ms
  @keydown.escape.window="menu = false"
  {{ $attributes->merge(['class' => 'fixed inset-0 z-60 flex h-dvh w-full items-center justify-center bg-header']) }}>
  <nav aria-label="Hauptnavigation">
    <ul class="flex flex-col items-center gap-y-32">
      @foreach ($items as $page)
        <x-menu.mobile.item :href="route($page['route'])" :active="request()->routeIs($page['route'])" :title="$page['title']" />
      @endforeach
    </ul>
  </nav>
</div>
