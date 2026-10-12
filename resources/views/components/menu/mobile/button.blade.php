<button
  type="button"
  @click="menu = !menu"
  :aria-expanded="menu"
  aria-label="Menü"
  {{ $attributes->merge(['class' => 'relative z-70 flex h-44 w-44 cursor-pointer items-center justify-center text-ink']) }}>
  <span x-show="!menu"><x-icons.burger class="h-auto w-26" /></span>
  <span x-show="menu" x-cloak><x-icons.cross class="h-auto w-26" /></span>
</button>
