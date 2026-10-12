{{-- Gemeinsame Felder von Anmelde- und Kontaktformular (App\Livewire\Concerns\ContactFields).
     $fields: street / zip_city / phone / message => 'required' | 'optional' | false
     $prefix: macht die IDs eindeutig, falls zwei Formulare auf einer Seite stehen. --}}
@props(['fields', 'prefix'])

@php
  $label = fn (string $text, string $field) => $text.($fields[$field] === 'required' ? '*' : '');
@endphp

<div class="grid gap-20 sm:grid-cols-2 md:gap-24">
  <x-form.input name="first_name" :id="$prefix.'-first_name'" label="Vorname*" wire:model.blur="first_name" autocomplete="given-name" />
  <x-form.input name="last_name" :id="$prefix.'-last_name'" label="Name*" wire:model.blur="last_name" autocomplete="family-name" />
</div>

@if ($fields['street'] || $fields['zip_city'])
  <div class="grid gap-20 sm:grid-cols-2 md:gap-24">
    @if ($fields['street'])
      <x-form.input name="street" :id="$prefix.'-street'" :label="$label('Strasse/Nr.', 'street')" wire:model.blur="street" autocomplete="street-address" />
    @endif
    @if ($fields['zip_city'])
      <x-form.input name="zip_city" :id="$prefix.'-zip_city'" :label="$label('PLZ/Ort', 'zip_city')" wire:model.blur="zip_city" autocomplete="postal-code" />
    @endif
  </div>
@endif

<div class="grid gap-20 sm:grid-cols-2 md:gap-24">
  <x-form.input name="email" :id="$prefix.'-email'" label="E-Mail*" type="email" wire:model.blur="email" autocomplete="email" />
  @if ($fields['phone'])
    <x-form.input name="phone" :id="$prefix.'-phone'" :label="$label('Telefon', 'phone')" type="tel" wire:model.blur="phone" autocomplete="tel" />
  @endif
</div>

@if ($fields['message'])
  <x-form.textarea name="message" :id="$prefix.'-message'" :label="$label('Nachricht', 'message')" wire:model.blur="message" rows="6" />
@endif

<div>
  <x-form.checkbox :id="$prefix.'-privacy'" wire:model.live="privacy" multiline :error="$errors->has('privacy')">
    Ich habe die
    <x-links.styled :href="route('page.privacy')" target="_blank">Datenschutzerklärung</x-links.styled>
    gelesen und akzeptiere diese.*
  </x-form.checkbox>
  <x-form.error name="privacy" :id="$prefix.'-privacy'" />
</div>

<x-form.honeypot :id="$prefix.'-website'" wire:model="website" />
<x-form.error name="turnstileToken" />
