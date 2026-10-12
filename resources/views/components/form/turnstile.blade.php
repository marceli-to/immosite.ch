{{-- Cloudflare Turnstile (unsichtbar). Rendert nur mit TURNSTILE_SITE_KEY und
     uebergibt das Token an die Livewire-Eigenschaft turnstileToken. --}}
@if (filled(config('services.turnstile.site_key')))
  <div wire:ignore x-data
    x-on:turnstile:reset.window="window.turnstile && $refs.widget.dataset.widgetId && window.turnstile.reset($refs.widget.dataset.widgetId)">
    <div x-ref="widget" x-init="
      const render = () => {
        $refs.widget.dataset.widgetId = window.turnstile.render($refs.widget, {
          sitekey: '{{ config('services.turnstile.site_key') }}',
          callback: (token) => $wire.set('turnstileToken', token),
          'expired-callback': () => $wire.set('turnstileToken', ''),
        });
      };
      window.turnstile ? render() : document.addEventListener('turnstile:loaded', render, { once: true });
    "></div>
  </div>
  @once
    @push('scripts')
      <script>window.onTurnstileLoad = () => document.dispatchEvent(new Event('turnstile:loaded'));</script>
      <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?onload=onTurnstileLoad" async defer></script>
    @endpush
  @endonce
@endif
