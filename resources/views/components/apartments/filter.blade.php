{{-- Filters above the table (resources/js/modules/apartment-filter.js).
     1 column → 2 at sm → one row at lg (docs/responsive-rules.md §5). --}}
@props(['filters'])

<div data-apartment-filters class="mb-32 grid grid-cols-1 gap-16 sm:grid-cols-2 lg:mb-48 lg:grid-flow-col lg:auto-cols-fr lg:items-end lg:gap-20">
  @foreach ($filters as $key => $filter)
    <div>
      <label for="filter-{{ $key }}" class="mb-8 block text-xs font-bold lg:text-sm">{{ $filter['label'] }}</label>
      <div class="relative">
        <select id="filter-{{ $key }}" data-apartment-filter="{{ $key }}"
          class="block w-full cursor-pointer appearance-none bg-surface py-12 pr-44 pl-16 text-sm ring-1 ring-field outline-none focus-visible:ring-2 focus-visible:ring-primary data-active:ring-2 data-active:ring-primary">
          <option value="">{{ config('apartments.filter_labels.all', 'Alle') }}</option>
          @foreach ($filter['options'] as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
          @endforeach
        </select>
        <x-icons.chevron-down class="pointer-events-none absolute top-1/2 right-14 h-auto w-16 -translate-y-1/2" />
      </div>
    </div>
  @endforeach

  <button type="button" data-apartment-filter-reset
    class="cursor-pointer py-12 text-left text-sm text-primary underline-offset-4 transition-colors hover:text-ink hover:underline">
    {{ config('apartments.filter_labels.reset', 'Filter zurücksetzen') }}
  </button>
</div>
