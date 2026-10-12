@props(['status'])

<span class="inline-flex items-center gap-8 whitespace-nowrap">
  <span aria-hidden="true" @class([
    'size-10 shrink-0 rounded-full',
    'bg-status-free' => $status === \App\Apartments\Status::Free,
    'bg-status-reserved' => $status === \App\Apartments\Status::Reserved,
    'bg-status-rented' => $status === \App\Apartments\Status::Rented,
  ])></span>
  {{ $status->label() }}
</span>
