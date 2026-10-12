{{-- Text + image column. Mobile: text first, image below (DOM order,
     docs/responsive-rules.md §5). From md two columns.
     bleed: the image runs to the window edge (50/50), the text stays aligned with the
     container; on mobile the image keeps the container padding (§5).
     Spacing: section 40 → 60 → 80 (§3). --}}
@props([
  'bg' => 'bg-surface',
  'ratio' => '7/5',
  'align' => 'start',
  'bleed' => false,
  'reverse' => false,
])

@if ($bleed)
  {{-- The outer padding puts the text on the container's left edge; percentages in
       padding refer to the full-width parent, so the scrollbar doesn't skew it. --}}
  <section @class([
    $bg,
    'md:pl-[max(0px,calc((100%-var(--container-site))/2))]' => ! $reverse,
    'md:pr-[max(0px,calc((100%-var(--container-site))/2))]' => $reverse,
  ])>
    <div @class(['grid grid-cols-1 md:grid-cols-2', 'md:items-center' => $align === 'center'])>
      <div @class(['prose-site min-w-0 px-(--gutter) py-40 md:py-60 lg:py-80', 'md:order-2' => $reverse]) data-reveal {{ $attributes }}>
        {{ $slot }}
      </div>
      @isset($aside)
        {{-- From md the image fills the column; the text decides the height. --}}
        <div class="relative min-w-0 px-(--gutter) pb-40 md:min-h-[400px] md:self-stretch md:px-0 md:pb-0" data-reveal>
          <div class="md:absolute md:inset-0">
            {{ $aside }}
          </div>
        </div>
      @endisset
    </div>
  </section>
@else
  @php
    $columns = [
      '1/1' => ['md:col-span-6', 'md:col-span-6'],
      '7/5' => ['md:col-span-7', 'md:col-span-5'],
      '5/7' => ['md:col-span-5', 'md:col-span-7'],
      '8/4' => ['md:col-span-8', 'md:col-span-4'],
    ][$ratio];
  @endphp

  <section class="{{ $bg }}">
    <x-layout.inner {{ $attributes->merge(['class' => 'py-40 md:py-60 lg:py-80']) }}>
      <div @class([
        'grid grid-cols-1 gap-y-32 md:grid-cols-12 md:gap-x-20 lg:gap-x-40',
        'md:items-center' => $align === 'center',
      ])>
        {{-- min-w-0: otherwise wide content (tables) stretches the column instead of scrolling. --}}
        <div @class(['prose-site min-w-0', $columns[0], 'md:order-2' => $reverse]) data-reveal>
          {{ $slot }}
        </div>
        @isset($aside)
          <div class="min-w-0 {{ $columns[1] }}" data-reveal>
            {{ $aside }}
          </div>
        @endisset
      </div>
    </x-layout.inner>
  </section>
@endif
