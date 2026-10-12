{{-- Isometry next to the apartment table (module:isometry). Mobile: sticky above the
     table with the section background, so rows scroll underneath it. From lg: right
     column, sticky below the header plus room for the lifted floors. top = --header-height,
     never more on mobile, or rows show through the gap. --}}
<div class="sticky top-(--header-height) z-20 -mx-(--gutter) mb-24 bg-surface-alt px-(--gutter) pt-8 pb-16 lg:top-[calc(var(--header-height)+80px)] lg:order-2 lg:mx-0 lg:mb-0 lg:w-[42%] lg:shrink-0 lg:self-start lg:bg-transparent lg:p-0">
  <x-isometry />
</div>
