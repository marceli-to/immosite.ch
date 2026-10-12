{{-- Key figures ("45 Loftwohnungen · 12 Kleinbüros · 10 Hobbyräume").
     items: [['number' => 45, 'label' => "moderne\nLoftwohnungen"], …]; \n breaks only from lg.
     1 column → 2 at sm → one row from lg (docs/responsive-rules.md §5). --}}
@props([
  'items' => [],
  'heading' => null,
])

<div {{ $attributes }} data-reveal>
  @if ($heading)
    <p class="mb-16 font-display text-lg font-bold lg:mb-24 lg:text-2xl">{{ $heading }}</p>
  @endif
  <dl class="grid gap-x-40 gap-y-16 sm:grid-cols-2 lg:grid-flow-col lg:auto-cols-fr">
    @foreach ($items as $item)
      <div class="flex items-center gap-x-12">
        <dt class="order-2 text-sm leading-tight lg:text-xl">{!! nl2br(e($item['label']), false) !!}</dt>
        <dd class="order-1 shrink-0 font-display text-5xl leading-none font-bold tabular-nums lg:text-7xl">{{ $item['number'] }}</dd>
      </div>
    @endforeach
  </dl>
</div>
