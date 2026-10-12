<body
  class="flex min-h-screen flex-col"
  x-data="{ menu: false }"
  {{-- Keep the page behind the open mobile menu from scrolling. Inline style,
       because <html> has overflow-y-scroll via a class and the class could win otherwise. --}}
  x-effect="document.documentElement.style.overflow = menu ? 'hidden' : ''">
  {{ $slot }}

  {{-- Alpine comes from Livewire. But Livewire only injects its script on pages
       that render a Livewire component. Without the explicit call, Alpine is
       missing on all other pages and the mobile menu doesn't work. --}}
  @livewireScripts

  @stack('scripts')
</body>
