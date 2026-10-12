<div>
  @if ($submitted)
    <div role="status" aria-live="polite">
      <x-headings.h2>Vielen Dank für Ihre Anmeldung</x-headings.h2>
      <p>Wir haben Ihre Angaben erhalten und informieren Sie, sobald die Vermietung startet.</p>
    </div>
  @else
    <x-headings.h2>Anmeldung</x-headings.h2>

    {{-- method="post" als Absicherung: Faellt Livewires JS aus, wuerde ein Formular
         ohne method per GET abschicken und die Personendaten in die URL schreiben
         (Verlauf, Server-Logs, Referrer). So schlaegt der Fallback sichtbar fehl. --}}
    <form wire:submit="submit" method="post" class="relative flex flex-col gap-20 md:gap-24">

      @if ($options)
        <fieldset>
          <legend class="mb-12 font-display text-xl leading-[1.3] font-bold lg:text-2xl">Ich interessiere mich für</legend>
          <div class="grid gap-x-40 gap-y-10 sm:grid-cols-2">
            @foreach ($options as $value => $label)
              <x-form.checkbox
                id="interest-{{ \Illuminate\Support\Str::slug($value) }}"
                value="{{ $value }}"
                wire:model.live="interests"
                :error="$errors->has('interests')">
                {{ $label }}
              </x-form.checkbox>
            @endforeach
          </div>
          <x-form.error name="interests" />
        </fieldset>
      @endif

      <div class="grid gap-20 sm:grid-cols-2 md:gap-24">
        <x-form.input name="first_name" label="Vorname*" wire:model.blur="first_name" autocomplete="given-name" />
        <x-form.input name="last_name" label="Name*" wire:model.blur="last_name" autocomplete="family-name" />
      </div>

      @if ($this->field('street') || $this->field('zip_city'))
        <div class="grid gap-20 sm:grid-cols-2 md:gap-24">
          @if ($mode = $this->field('street'))
            <x-form.input name="street" :label="'Strasse/Nr.'.($mode === 'required' ? '*' : '')" wire:model.blur="street" autocomplete="street-address" />
          @endif
          @if ($mode = $this->field('zip_city'))
            <x-form.input name="zip_city" :label="'PLZ/Ort'.($mode === 'required' ? '*' : '')" wire:model.blur="zip_city" autocomplete="postal-code" />
          @endif
        </div>
      @endif

      <div class="grid gap-20 sm:grid-cols-2 md:gap-24">
        <x-form.input name="email" label="E-Mail*" type="email" wire:model.blur="email" autocomplete="email" />
        @if ($mode = $this->field('phone'))
          <x-form.input name="phone" :label="'Telefon'.($mode === 'required' ? '*' : '')" type="tel" wire:model.blur="phone" autocomplete="tel" />
        @endif
      </div>

      @if ($mode = $this->field('message'))
        <x-form.textarea name="message" :label="'Nachricht'.($mode === 'required' ? '*' : '')" wire:model.blur="message" rows="6" />
      @endif

      <div>
        <x-form.checkbox id="privacy" wire:model.live="privacy" multiline :error="$errors->has('privacy')">
          Ich habe die
          <x-links.styled :href="route('page.privacy')" target="_blank">Datenschutzerklärung</x-links.styled>
          gelesen und akzeptiere diese.*
        </x-form.checkbox>
        <x-form.error name="privacy" />
      </div>

      <x-form.honeypot wire:model="website" />
      <x-form.error name="turnstileToken" />

      <div>
        <x-buttons.primary type="submit" class="disabled:opacity-60" wire:loading.attr="disabled" wire:target="submit">
          <span wire:loading.remove wire:target="submit">Absenden</span>
          <span wire:loading wire:target="submit">Wird gesendet…</span>
        </x-buttons.primary>
      </div>
    </form>

    <x-form.turnstile />
  @endif
</div>
