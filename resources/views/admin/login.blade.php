@extends('admin.layout')
@section('title', 'Anmelden')

@section('content')
  <div class="mx-auto max-w-[420px] bg-surface p-24 lg:p-32">
    <x-headings.h2>Anmelden</x-headings.h2>
    <form method="post" action="{{ route('admin.login') }}" class="flex flex-col gap-16">
      @csrf
      <x-form.input name="email" label="E-Mail" type="email" :value="old('email')" autocomplete="username" required />
      <x-form.input name="password" label="Passwort" type="password" autocomplete="current-password" required />
      <x-form.checkbox id="remember" name="remember" value="1">Angemeldet bleiben</x-form.checkbox>
      <div>
        <x-buttons.primary type="submit">Anmelden</x-buttons.primary>
      </div>
    </form>
  </div>
@endsection
