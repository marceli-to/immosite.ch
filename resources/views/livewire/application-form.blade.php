<div>
  @php($refs = $this->refs())
  <x-headings.h2>
    @if (count($refs) === 1)
      Bewerbung für Wohnung {{ $refs[0] }}
    @else
      Bewerbung
    @endif
  </x-headings.h2>

  {{-- method="post": see registration-form (no GET fallback with personal data in the URL). --}}
  <form wire:submit="submit" method="post" class="relative flex flex-col gap-20 md:gap-24">
    @if ($this->multiple() && ($options = $this->options()))
      <fieldset>
        <legend class="mb-12 font-bold">Wohnungen*</legend>

        @if ($selected)
          <ul class="mb-12 flex flex-wrap gap-8" aria-label="Ausgewählte Wohnungen">
            @foreach ($selected as $ref)
              <li wire:key="selected-{{ $ref }}" class="inline-flex items-center gap-4 bg-primary py-4 pr-4 pl-12 text-xs text-on-primary lg:text-sm">
                {{ $options[$ref] ?? $ref }}
                <button type="button" wire:click="remove(@js($ref))" aria-label="Wohnung {{ $ref }} entfernen"
                  class="flex size-32 cursor-pointer items-center justify-center transition-opacity hover:opacity-70">
                  <x-icons.cross class="size-12" />
                </button>
              </li>
            @endforeach
          </ul>
        @endif

        @if ($remaining = array_diff_key($options, array_flip($selected)))
          <label for="apply-add" class="sr-only">Wohnung hinzufügen</label>
          <div class="relative">
            <select id="apply-add" wire:model.live="add"
              @error('selected') aria-invalid="true" aria-describedby="selected-error" @enderror
              @class([
                'block w-full cursor-pointer appearance-none py-13 pr-44 pl-16 text-ink outline-none transition focus-visible:ring-2 focus-visible:ring-primary',
                'bg-surface ring-1 ring-field' => ! $errors->has('selected'),
                'bg-error-bg ring-1 ring-error' => $errors->has('selected'),
              ])>
              <option value="">{{ $selected ? 'Weitere Wohnung hinzufügen …' : 'Wohnung wählen …' }}</option>
              @foreach ($remaining as $ref => $label)
                <option value="{{ $ref }}">{{ $label }}</option>
              @endforeach
            </select>
            <x-icons.chevron-down class="pointer-events-none absolute top-1/2 right-14 h-auto w-16 -translate-y-1/2" />
          </div>
        @endif
        <x-form.error name="selected" />
      </fieldset>
    @endif

    <x-form.contact-fields :fields="$this->fields()" prefix="apply" />
    <x-form.error name="send" />

    <div>
      <x-buttons.primary type="submit" class="disabled:opacity-60" wire:loading.attr="disabled" wire:target="submit">
        <span wire:loading.remove wire:target="submit">Bewerbung senden</span>
        <span wire:loading wire:target="submit">Wird gesendet…</span>
      </x-buttons.primary>
    </div>
  </form>

  <x-form.turnstile />
</div>
