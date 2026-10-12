@extends('app')
@section('meta_title', 'Impressum')

@php
  $marketer = config('site.marketer');
  $design = config('site.credits.design');
  $development = config('site.credits.development');
@endphp

@section('content')

  <x-sections.text>
    <x-headings.h1>Impressum</x-headings.h1>

    <x-headings.h3>{{ $marketer['role'] }}</x-headings.h3>
    <p>
      {{ $marketer['name'] }}<br>
      {{ $marketer['street'] }}<br>
      {{ $marketer['city'] }}<br>
      <a href="mailto:{{ $marketer['email'] }}">{{ $marketer['email'] }}</a>
    </p>

    @if ($design)
      <x-headings.h3>Konzept &amp; Gestaltung</x-headings.h3>
      <p>
        {{ $design['name'] }}<br>
        {{ $design['street'] }}<br>
        {{ $design['city'] }}<br>
        <a href="mailto:{{ $design['email'] }}">{{ $design['email'] }}</a><br>
        <a href="{{ $design['url'] }}" target="_blank" rel="noopener noreferrer">{{ preg_replace('#^https?://#', '', $design['url']) }}</a>
      </p>
    @endif

    <x-headings.h3>Programmierung</x-headings.h3>
    <p>
      {{ $development['name'] }}<br>
      <a href="{{ $development['url'] }}" target="_blank" rel="noopener noreferrer">{{ preg_replace('#^https?://#', '', $development['url']) }}</a>
    </p>
  </x-sections.text>

@endsection
