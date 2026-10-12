<body class="flex min-h-screen flex-col" x-data="{ menu: false }">
  {{ $slot }}

  {{-- Alpine kommt aus Livewire. Livewire spielt sein Script aber nur auf Seiten
       aus, die eine Livewire-Komponente rendern. Ohne den expliziten Aufruf
       fehlt Alpine auf allen anderen Seiten und das Mobile-Menue bleibt tot. --}}
  @livewireScripts

  @stack('scripts')
</body>
