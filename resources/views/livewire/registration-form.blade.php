<div>
  @if ($submitted)
    <div role="status" aria-live="polite">
      <x-headings.h2>{{ config('registration.success_heading') }}</x-headings.h2>
      <p>{{ config('registration.success_text') }}</p>
    </div>
  @else
    @if ($heading = config('registration.heading'))
      <x-headings.h2>{{ $heading }}</x-headings.h2>
    @endif

    {{-- method="post" as a safeguard: if Livewire's JS fails, a form without
         method would submit via GET and put the personal data in the URL
         (history, server logs, referrer). This way the fallback fails visibly. --}}
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

      <x-form.contact-fields :fields="$this->fields()" prefix="registration" />

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
