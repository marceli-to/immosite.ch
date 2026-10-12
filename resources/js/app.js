// Alpine ships with Livewire 4 and is started there too. A separate Alpine
// import with Alpine.start() runs as a second instance and breaks Livewire's
// initialisation: wire:submit no longer binds and forms fall back to a
// native GET submit (form data ends up in the URL).
// So register plugins on Livewire's Alpine via livewire:init:
//
// document.addEventListener('livewire:init', () => {
//   window.Alpine.plugin(collapse);
// });

import './modules/map.js'; // module:map
import './modules/gallery.js'; // module:gallery
