{{-- Apartment table, columns from config('apartments.columns').
     Mobile: all columns kept, the wrapper scrolls sideways and headers use the
     short label (docs/responsive-rules.md §7). --}}
@props(['apartments', 'repository'])

@use('App\Apartments\Format')

@php
  $columns = config('apartments.columns');
  $hidePrices = config('apartments.hide_prices_when_rented');
  $numeric = ['rooms', 'area', 'outdoor_area', 'rent_net', 'charges', 'rent_gross', 'price'];
@endphp

{{-- contain-paint besides scrolling: the table's full width otherwise still
     leaks up into <html>'s scrollWidth, and some browsers then treat the page
     as too wide (seen on Hüningerstrasse at 375 px). --}}
<div class="overflow-x-auto contain-paint">
  <table class="min-w-full border-collapse text-left text-xs lg:text-sm">
    <thead>
      <tr class="border-b border-ink align-bottom">
        @foreach ($columns as $key => [$label, $short])
          <th scope="col" @class([
            'py-10 pr-12 font-bold whitespace-nowrap lg:pr-16',
            'text-right' => in_array($key, $numeric),
            'text-center' => $key === 'plan',
          ])>
            @if ($label === $short)
              {{ $label }}
            @else
              <span class="md:hidden">{{ $short }}</span><span class="hidden md:inline">{{ $label }}</span>
            @endif
          </th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach ($apartments as $apartment)
        @php($showPrice = ! ($hidePrices && $apartment->status === \App\Apartments\Status::Rented))
        <tr
          data-apartment="{{ $apartment->ref }}"
          data-status="{{ $apartment->status->value }}"
          data-rooms="{{ $apartment->roomsLabel() }}"
          data-floor="{{ $apartment->floor }}"
          data-house="{{ $apartment->house }}"
          class="border-b border-line transition-colors hover:bg-ink/5">
          @foreach ($columns as $key => $column)
            <td @class([
              'py-10 pr-12 whitespace-nowrap lg:pr-16',
              'text-right tabular-nums' => in_array($key, $numeric),
              'text-center' => $key === 'plan',
            ])>
              @switch($key)
                @case('ref') {{ $apartment->ref }} @break
                @case('house') {{ $repository->houseLabel($apartment->house) }} @break
                @case('floor') {{ $repository->floorLabel($apartment->floor) }} @break
                @case('rooms') {{ $apartment->roomsLabel() }} @break
                @case('area') {{ Format::area($apartment->area) }} @break
                @case('outdoor_area') {{ Format::area($apartment->outdoorArea) }} @break
                @case('rent_net') {{ $showPrice ? Format::money($apartment->rentNet) : '' }} @break
                @case('charges') {{ $showPrice ? Format::money($apartment->charges) : '' }} @break
                @case('rent_gross') {{ $showPrice ? Format::money($apartment->rentGross) : '' }} @break
                @case('price') {{ $showPrice ? Format::money($apartment->price) : '' }} @break
                @case('available_from') {{ Format::date($apartment->availableFrom) }} @break
                @case('plan')
                  @if ($apartment->planUrl)
                    <a href="{{ $apartment->planUrl }}" target="_blank" rel="noopener" aria-label="Grundriss {{ $apartment->ref }} öffnen"
                      class="inline-flex size-32 items-center justify-center text-primary transition-colors hover:text-ink">
                      <x-icons.download class="size-16" />
                    </a>
                  @else
                    <span title="Grundriss folgt" class="inline-flex size-32 items-center justify-center text-muted/50">
                      <x-icons.download class="size-16" aria-hidden="true" />
                      <span class="sr-only">Grundriss folgt</span>
                    </span>
                  @endif
                  @break
                @case('status') <x-apartments.status :status="$apartment->status" /> @break
              @endswitch
            </td>
          @endforeach
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
