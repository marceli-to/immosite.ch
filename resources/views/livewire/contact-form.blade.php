<div>
  <x-headings.h2>Kontakt</x-headings.h2>

  {{-- method="post": see registration-form (no GET fallback with personal data in the URL). --}}
  <form wire:submit="submit" method="post" class="relative flex flex-col gap-20 md:gap-24">

    <x-form.contact-fields :fields="$this->fields()" prefix="contact" />
    <x-form.error name="send" />

    <div>
      <x-buttons.primary type="submit" class="disabled:opacity-60" wire:loading.attr="disabled" wire:target="submit">
        <span wire:loading.remove wire:target="submit">Absenden</span>
        <span wire:loading wire:target="submit">Wird gesendet…</span>
      </x-buttons.primary>
    </div>
  </form>

  <x-form.turnstile />
</div>
