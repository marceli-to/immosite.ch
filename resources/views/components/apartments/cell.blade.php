{{-- One value of the apartment table, used for the cells and the mobile detail list. --}}
@props(['key', 'apartment', 'repository', 'showPrice' => true])

@use('App\Apartments\Format')

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
  @case('apply') <x-apartments.apply :apartment="$apartment" /> @break {{-- module:apply --}}
@endswitch
