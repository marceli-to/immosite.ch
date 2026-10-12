// Alpine wird von Livewire 4 mitgeliefert und dort auch gestartet. Ein eigener
// Alpine-Import mit Alpine.start() laeuft als zweite Instanz und bricht Livewires
// Initialisierung ab: wire:submit bindet dann nicht mehr und Formulare fallen
// auf einen nativen GET-Submit zurueck (Formulardaten landen in der URL).
// Plugins deshalb ueber livewire:init an Livewires Alpine registrieren:
//
// document.addEventListener('livewire:init', () => {
//   window.Alpine.plugin(collapse);
// });
