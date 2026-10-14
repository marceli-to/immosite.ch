{{-- Parking spaces and other extras below the apartments (config apartments.extras).
     Static rows: the agencies list these as a type with one price, not per unit,
     so they don't come from the apartment source. Not affected by the filters. --}}
@props(['extras' => config('apartments.extras')])

@use('App\Apartments\Format')

@php
  $rows = $extras['rows'] ?? [];
  $columns = $extras['columns'] ?? [];
@endphp

@if ($rows)
  <div {{ $attributes }}>
    @if ($extras['heading'] ?? null)
      <x-headings.h2>{{ $extras['heading'] }}</x-headings.h2>
    @endif
    <div class="overflow-x-auto contain-paint">
      <table class="min-w-full border-collapse text-left text-xs lg:text-sm">
        <thead>
          <tr class="border-b border-ink align-bottom">
            @foreach ($columns as $key => $label)
              <th scope="col" @class(['py-10 pr-12 font-bold whitespace-nowrap lg:pr-16', 'text-right' => $key === 'price'])>{{ $label }}</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach ($rows as $row)
            <tr class="border-b border-line">
              @foreach ($columns as $key => $label)
                <td @class(['py-10 pr-12 lg:pr-16', 'text-right tabular-nums whitespace-nowrap' => $key === 'price'])>
                  @switch($key)
                    @case('price') {{ is_int($row['price'] ?? null) ? Format::money($row['price']) : ($row['price'] ?? '') }} @break
                    @case('status') @if (isset($row['status'])) <x-apartments.status :status="\App\Apartments\Status::from($row['status'])" /> @endif @break
                    @default {{ $row[$key] ?? '' }}
                  @endswitch
                </td>
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endif
