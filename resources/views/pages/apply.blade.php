@extends('app')
@section('meta_title', 'Bewerbung')

{{-- Reached from the apartment table only, not meant for search engines. --}}
@push('head')
  <meta name="robots" content="noindex">
@endpush

@section('content')

  <section class="bg-surface">
    <x-layout.inner class="py-40 md:py-60 lg:py-80">
      <div class="max-w-text">
        <p class="mb-24"><x-links.styled :href="route('page.apartments')">← Zurück zu den Wohnungen</x-links.styled></p>
        <livewire:application-form />
      </div>
    </x-layout.inner>
  </section>

@endsection
