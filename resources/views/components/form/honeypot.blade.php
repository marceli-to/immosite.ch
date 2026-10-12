{{-- Honeypot: fuer Menschen unsichtbar, Bots fuellen es aus. Nicht display:none,
     weil manche Bots solche Felder auslassen; stattdessen ausserhalb des Bildes. --}}
<div aria-hidden="true" class="absolute -left-[9999px] h-1 w-1 overflow-hidden">
  <label for="website">Website</label>
  <input type="text" id="website" name="website" tabindex="-1" autocomplete="off" {{ $attributes }}>
</div>
