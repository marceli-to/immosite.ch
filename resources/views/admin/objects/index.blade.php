@extends('admin.layout')
@section('title', 'Gewerbeflächen')

@section('content')
  <x-headings.h2>Gewerbeflächen</x-headings.h2>

  @if (session('status'))
    <p role="status" class="mb-24 bg-surface p-16">{{ session('status') }}</p>
  @endif

  @if ($errors->any())
    <div role="alert" class="mb-24 bg-error-bg p-16 text-error">
      @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
      @endforeach
    </div>
  @endif

  <form method="post" action="{{ route('admin.objects.update') }}">
    @csrf
    @method('put')

    <div class="overflow-x-auto bg-surface">
      <table class="min-w-full text-left text-sm">
        <thead>
          <tr class="border-b border-ink">
            <th class="p-12">Objekt</th>
            <th class="p-12">Etage</th>
            <th class="p-12 text-right">Fläche m²</th>
            <th class="p-12">Status</th>
            <th class="p-12">Nettomiete CHF</th>
            <th class="p-12">Nebenkosten CHF</th>
            <th class="p-12">Zuletzt geändert</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($objects as $object)
            <tr class="border-b border-line">
              <td class="p-12 font-bold">{{ $object->ref }}</td>
              <td class="p-12">{{ $object->floor }}</td>
              <td class="p-12 text-right">{{ $object->area }}</td>
              <td class="p-12">
                <label class="sr-only" for="status-{{ $object->id }}">Status {{ $object->ref }}</label>
                <select id="status-{{ $object->id }}" name="objects[{{ $object->id }}][status]" class="bg-surface p-8 ring-1 ring-line">
                  @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(old("objects.{$object->id}.status", $object->status) === $value)>{{ $label }}</option>
                  @endforeach
                </select>
              </td>
              <td class="p-12">
                <label class="sr-only" for="rent-{{ $object->id }}">Nettomiete {{ $object->ref }}</label>
                <input id="rent-{{ $object->id }}" type="number" min="0" step="1" name="objects[{{ $object->id }}][rent_net]" value="{{ old("objects.{$object->id}.rent_net", $object->rent_net) }}" class="w-120 bg-surface p-8 ring-1 ring-line">
              </td>
              <td class="p-12">
                <label class="sr-only" for="charges-{{ $object->id }}">Nebenkosten {{ $object->ref }}</label>
                <input id="charges-{{ $object->id }}" type="number" min="0" step="1" name="objects[{{ $object->id }}][charges]" value="{{ old("objects.{$object->id}.charges", $object->charges) }}" class="w-120 bg-surface p-8 ring-1 ring-line">
              </td>
              <td class="p-12 text-xs text-muted">
                {{ $object->updated_at?->timezone('Europe/Zurich')->format('d.m.Y H:i') }}
                @if ($object->updated_by) · {{ $object->updated_by }} @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-24">
      <x-buttons.primary type="submit">Speichern</x-buttons.primary>
    </div>
  </form>
@endsection
