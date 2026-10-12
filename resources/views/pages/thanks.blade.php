@extends('app')
@section('meta_title', 'Vielen Dank')

{{-- Only meant as the target after submitting, not for search engines. --}}
@push('head')
  <meta name="robots" content="noindex">
@endpush

@section('content')

  <x-sections.text bg="bg-surface-alt">
    <div role="status" aria-live="polite">
      <x-headings.h1>Vielen Dank für Ihre Anfrage</x-headings.h1>
      <p>Wir haben Ihre Nachricht erhalten und melden uns zeitnah bei Ihnen. Eine Bestätigung haben wir Ihnen per E-Mail geschickt.</p>
      <p><x-links.styled :href="route('page.home')">Zurück zur Startseite</x-links.styled></p>
    </div>
  </x-sections.text>

@endsection
