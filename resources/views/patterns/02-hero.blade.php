{{-- x-sections.hero (+ x-sections.stats): fullscreen or :fullscreen="false" with aspect;
     <x-slot:media> for a slider instead of the image --}}
<x-sections.hero image="img/placeholder-1" alt="Beispiel" :fullscreen="false">
  <x-headings.h1 class="mb-8!">live &amp; work</x-headings.h1>
  <p class="text-lg lg:text-2xl">Zürich-Altstetten</p>
  <x-slot:stats>
    <x-sections.stats heading="Bis Mai 2027 entstehen" :items="[['number' => 45, 'label' => 'moderne Loftwohnungen'], ['number' => 12, 'label' => 'Kleinbüros'], ['number' => 10, 'label' => 'Hobbyräume']]" />
  </x-slot:stats>
</x-sections.hero>
