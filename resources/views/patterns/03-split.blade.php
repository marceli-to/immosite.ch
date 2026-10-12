{{-- x-sections.split: ratio 1/1 · 7/5 · 5/7 · 8/4, align, reverse; bleed = image to the window edge --}}
<x-sections.split ratio="7/5">
  <x-headings.h2>Text und Bild, 7/5</x-headings.h2>
  <p>Mobile: Text zuerst, Bild darunter. Ab md zwei Spalten.</p>
  <x-slot:aside><x-media.picture src="img/placeholder-2" alt="Beispiel" class="aspect-[4/3]" /></x-slot:aside>
</x-sections.split>

<x-sections.split bleed reverse bg="bg-surface-alt" align="center">
  <x-headings.h2>bleed reverse</x-headings.h2>
  <p>Das Bild läuft bis an den Fensterrand, der Text bleibt auf der Container-Kante.</p>
  <x-slot:aside><x-media.picture src="img/placeholder-3" alt="Beispiel" class="aspect-[4/3] md:aspect-auto md:h-full" /></x-slot:aside>
</x-sections.split>
