{{-- Distanztabelle Ziel × Verkehrsmittel aus config/location.php.
     Auf schmalen Bildschirmen seitlich scrollbar statt gequetscht (§7). --}}
@php
  $modes = config('location.modes');
  $rows = config('location.distances');
@endphp

<div class="-mx-20 overflow-x-auto px-20 md:mx-0 md:px-0">
  <table class="w-full min-w-[480px] text-left text-xs lg:text-sm">
    <caption class="sr-only">Distanzen in Minuten</caption>
    <thead>
      <tr class="border-b border-ink">
        <th scope="col" class="py-8 pr-16 font-bold">Ziel</th>
        @foreach ($modes as $label)
          <th scope="col" class="py-8 pr-16 font-bold whitespace-nowrap">{{ $label }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach ($rows as $row)
        <tr class="border-b border-line">
          <th scope="row" class="py-8 pr-16 font-normal">{{ $row['destination'] }}</th>
          @foreach (array_keys($modes) as $mode)
            <td class="py-8 pr-16 whitespace-nowrap">
              @if (filled($row[$mode] ?? null))
                {{ $row[$mode] }} Min.
              @else
                <span aria-label="keine Angabe">–</span>
              @endif
            </td>
          @endforeach
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
