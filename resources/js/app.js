import collapse from '@alpinejs/collapse';

// Alpine ships with Livewire 4 and is started there. A separate Alpine import with
// Alpine.start() runs as a second instance and breaks Livewire's initialisation:
// wire:submit no longer binds and forms fall back to a native GET submit (form data
// ends up in the URL). So plugins are registered on Livewire's Alpine via livewire:init.
document.addEventListener('livewire:init', () => {
  // Smooth open/close for accordions (x-collapse); Scala's accordion jumped.
  window.Alpine.plugin(collapse);
});

import './modules/map.js'; // module:map
import './modules/gallery.js'; // module:gallery
import './modules/apartment-filter.js'; // module:apartments
import './modules/isometry.js'; // module:isometry
import './modules/scroll-reveal.js'; // module:scroll-reveal
