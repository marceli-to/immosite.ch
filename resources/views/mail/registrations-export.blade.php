<x-mail::message>
# Neue Anmeldungen – {{ config('site.name') }}

Zeitraum: **{{ $period }}**

@if ($registrations->isEmpty())
Im Zeitraum sind keine neuen Anmeldungen eingegangen.
@else
Es sind **{{ $registrations->count() }}** neue Anmeldung(en) eingegangen. Die vollständige Liste
finden Sie in der beigefügten CSV-Datei.

@foreach ($registrations as $r)
- **{{ $r->fullName() }}** – {{ $r->email }}{{ $r->phone ? ', '.$r->phone : '' }}@if ($r->interests) ({{ implode(', ', $r->interestLabels()) }})@endif

@endforeach
@endif
</x-mail::message>
