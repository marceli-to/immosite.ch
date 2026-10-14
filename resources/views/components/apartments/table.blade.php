{{-- Apartment table, columns from config('apartments.columns') or the columns prop
     (commercial units use their own).
     Mobile (config apartments.mobile):
     'list'   = only apartments.mobile_columns stay in the row; a toggle opens the other
                values below it (pappelstrasse, nidum). Tapping it also marks the
                apartment in the isometry.
     'scroll' = all columns kept, the wrapper scrolls sideways, short headers
                (docs/responsive-rules.md §7).
     Each apartment is its own <tbody>, so row and details filter and highlight together. --}}
@props(['apartments', 'repository', 'columns' => null])

@php
  // [label, short label, header visually hidden?]
  $columns = array_map(fn ($c) => $c + [2 => false], $columns ?? config('apartments.columns'));
  $hidePrices = config('apartments.hide_prices_when_rented');
  $numeric = ['rooms', 'area', 'outdoor_area', 'rent_net', 'charges', 'rent_gross', 'price'];
  $list = config('apartments.mobile') === 'list';
  $summary = $list ? array_values(array_intersect(array_keys($columns), config('apartments.mobile_columns', []))) : array_keys($columns);
  $details = array_diff(array_keys($columns), $summary);
  $list = $list && $details;
@endphp

{{-- contain-paint besides scrolling: the table's full width otherwise still
     leaks up into <html>'s scrollWidth, and some browsers then treat the page
     as too wide (seen on Hüningerstrasse at 375 px). --}}
<div class="overflow-x-auto contain-paint">
  <table class="min-w-full border-collapse text-left text-xs lg:text-sm">
    <thead>
      <tr class="border-b border-ink align-bottom">
        @foreach ($columns as $key => [$label, $short, $hidden])
          <th scope="col" @class([
            'py-10 pr-12 font-bold whitespace-nowrap lg:pr-16',
            'text-right' => in_array($key, $numeric),
            'text-center' => $key === 'plan',
            'hidden md:table-cell' => $list && ! in_array($key, $summary),
          ])>
            @if ($hidden)
              <span class="sr-only">{{ $label }}</span>
            @elseif ($label === $short)
              {{ $label }}
            @else
              <span class="md:hidden">{{ $short }}</span><span class="hidden md:inline">{{ $label }}</span>
            @endif
          </th>
        @endforeach
        @if ($list)
          <th scope="col" class="md:hidden"><span class="sr-only">Details</span></th>
        @endif
      </tr>
    </thead>
    @foreach ($apartments as $apartment)
      @php($showPrice = ! ($hidePrices && $apartment->status === \App\Apartments\Status::Rented))
      @php($detailsId = 'apartment-details-'.\Illuminate\Support\Str::slug($apartment->ref).'-'.$loop->index)
      <tbody
        data-apartment="{{ $apartment->ref }}"
        data-status="{{ $apartment->status->value }}"
        data-rooms="{{ $apartment->roomsLabel() }}"
        data-floor="{{ $apartment->floor }}"
        data-house="{{ $apartment->house }}"
        @if ($list)
          x-data="{ open: false }"
          x-on:apartment-open="open = true"
        @endif
        class="border-b border-line transition-colors hover:bg-ink/5">
        <tr>
          @foreach ($columns as $key => $column)
            <td @class([
              'py-10 pr-12 whitespace-nowrap lg:pr-16',
              'text-right tabular-nums' => in_array($key, $numeric),
              'text-center' => $key === 'plan',
              'hidden md:table-cell' => $list && ! in_array($key, $summary),
            ])>
              <x-apartments.cell :key="$key" :apartment="$apartment" :repository="$repository" :show-price="$showPrice" />
            </td>
          @endforeach
          @if ($list)
            <td class="w-1 text-right md:hidden">
              <button type="button" x-on:click="open = ! open; $dispatch('apartments:toggle', { ref: @js($apartment->ref), open })" :aria-expanded="open" aria-controls="{{ $detailsId }}"
                aria-label="Wohnung {{ $apartment->ref }}: Details"
                class="-my-6 inline-flex size-44 cursor-pointer items-center justify-center text-primary">
                <x-icons.chevron-down class="h-auto w-16 transition-transform" ::class="open && 'rotate-180'" />
              </button>
            </td>
          @endif
        </tr>
        @if ($list)
          <tr id="{{ $detailsId }}" x-show="open" x-cloak class="md:hidden">
            <td colspan="{{ count($summary) + 1 }}" class="pb-16">
              <dl class="grid grid-cols-[auto_1fr] items-center gap-x-16 gap-y-6">
                @foreach ($details as $key)
                  @php($value = view('components.apartments.cell', ['key' => $key, 'apartment' => $apartment, 'repository' => $repository, 'showPrice' => $showPrice])->render())
                  @continue(trim(strip_tags($value, '<a><svg>')) === '')
                  @if ($columns[$key][2])
                    {{-- Header hidden (e.g. the apply button): no label, the value speaks for itself. --}}
                    <dd class="col-span-2 mt-6">{!! $value !!}</dd>
                  @else
                    <dt class="text-muted">{{ $columns[$key][0] }}</dt>
                    <dd class="text-right">{!! $value !!}</dd>
                  @endif
                @endforeach
              </dl>
            </td>
          </tr>
        @endif
      </tbody>
    @endforeach
  </table>
</div>
