{{-- Set before first paint, so revealed content doesn't flash and then hide
     (module:scroll-reveal). If the JS module hasn't started after 2.5 s
     (blocked, failed), show everything. --}}
<script>
  document.documentElement.classList.add('reveal-ready');
  setTimeout(() => window.revealStarted || document.documentElement.classList.remove('reveal-ready'), 2500);
</script>
