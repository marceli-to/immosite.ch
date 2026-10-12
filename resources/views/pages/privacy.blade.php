@extends('app')
@section('meta_title', 'Datenschutz')

@section('content')

  <x-sections.text>
    <x-headings.h1>Datenschutzerklärung</x-headings.h1>

    {{-- One section per file, so modules can add and remove their part
         with a single line (module:<name>). --}}
    @include('pages.privacy.intro')
    @include('pages.privacy.cookies')
    @include('pages.privacy.hosting')
    @include('pages.privacy.forms') {{-- module:registration,contact-form --}}
    @include('pages.privacy.mapbox') {{-- module:map --}}
    @includeWhen(config('services.google_analytics.id'), 'pages.privacy.analytics')
    @includeWhen(config('site.fonts.stylesheet'), 'pages.privacy.adobe-fonts')
    @include('pages.privacy.rights')
  </x-sections.text>

@endsection
