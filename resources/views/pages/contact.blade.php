@extends('app')
@section('meta_title', 'Kontakt')
@section('meta_description', 'Kontakt zur Vermarktung: Fragen zum Projekt und zu den Wohnungen.')

@section('content')

  <x-sections.text bg="bg-surface-alt">
    <x-headings.h1>Kontakt</x-headings.h1>
    <p>
      Platzhalter: Interesse an einer Wohnung? Hinterlassen Sie uns Ihre Angaben, wir melden uns bei Ihnen.
    </p>
    <p>
      {{ config('site.marketer.name') }}<br>
      {{ config('site.marketer.contact') }}, {{ config('site.marketer.role') }}<br>
      <a href="tel:{{ preg_replace('/\s+/', '', config('site.marketer.phone')) }}">{{ config('site.marketer.phone') }}</a><br>
      <a href="mailto:{{ config('site.marketer.email') }}">{{ config('site.marketer.email') }}</a>
    </p>
  </x-sections.text>

  @include('sections.contact')

@endsection
