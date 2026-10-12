<body
  class="flex min-h-screen flex-col"
  x-data="{ menu: false }"
  {{-- Seite hinter dem offenen Mobile-Menue nicht mitscrollen lassen. Inline-Style,
       weil <html> per Klasse overflow-y-scroll hat und die Klasse sonst gewinnen kann. --}}
  x-effect="document.documentElement.style.overflow = menu ? 'hidden' : ''">
  {{ $slot }}

  {{-- Alpine kommt aus Livewire. Livewire spielt sein Script aber nur auf Seiten
       aus, die eine Livewire-Komponente rendern. Ohne den expliziten Aufruf
       fehlt Alpine auf allen anderen Seiten und das Mobile-Menue bleibt tot. --}}
  @livewireScripts

  @stack('scripts')
</body>
