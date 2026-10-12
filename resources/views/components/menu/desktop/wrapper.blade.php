@props(['items'])

<nav {{ $attributes }} aria-label="Hauptnavigation">
  <ul class="flex items-baseline gap-x-24 lg:gap-x-28">
    @foreach ($items as $page)
      <x-menu.desktop.item :href="route($page['route'])" :active="request()->routeIs($page['route'])" :title="$page['title']" />
    @endforeach
  </ul>
</nav>
