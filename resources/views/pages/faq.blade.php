@extends('app')
@section('meta_title', 'FAQ')
@section('meta_description', 'Häufige Fragen zum Projekt und zur Vermietung.')

@section('content')

  <x-sections.text>
    <x-headings.h1>Häufige Fragen</x-headings.h1>
    <p>Platzhalter: Kurze Einleitung. Ihre Frage ist nicht dabei? Kontaktieren Sie uns.</p>

    <x-accordion.group class="mt-32 lg:mt-48">
      @foreach (config('faq') as $item)
        <x-accordion.item :title="$item['question']">
          {!! $item['answer'] !!}
        </x-accordion.item>
      @endforeach
    </x-accordion.group>
  </x-sections.text>

@endsection
