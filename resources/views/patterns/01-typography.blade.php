{{-- x-headings.h1/h2/h3, body text, x-links.styled, x-buttons.primary/secondary --}}
<x-sections.text>
  <x-headings.h1>Überschrift h1</x-headings.h1>
  <x-headings.h2>Überschrift h2</x-headings.h2>
  <x-headings.h3>Überschrift h3</x-headings.h3>
  <p>Fliesstext mit <x-links.styled href="#">Link</x-links.styled> und <strong>fetter Auszeichnung</strong>. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor.</p>
  <ul><li>Listenpunkt eins</li><li>Listenpunkt zwei</li></ul>
  <div class="flex flex-wrap gap-16">
    <x-buttons.primary href="#">Primär <x-icons.arrow-right class="h-auto w-18" /></x-buttons.primary>
    <x-buttons.secondary href="#"><x-icons.download class="size-16" /> Sekundär</x-buttons.secondary>
  </div>
</x-sections.text>
