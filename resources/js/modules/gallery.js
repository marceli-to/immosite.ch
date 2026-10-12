// Bilder-Slider (module:gallery). Swiper wird nur geladen, wenn die Seite einen
// Slider mit mehr als einem Bild hat; Einstellungen kommen per data-gallery.

async function initSliders(sliders) {
  const [{ default: Swiper }, { Navigation, Pagination, Autoplay, EffectFade, A11y, Keyboard }] = await Promise.all([
    import('swiper'),
    import('swiper/modules'),
    import('swiper/css'),
    import('swiper/css/effect-fade'),
  ]);

  // Wer Bewegung im System reduziert hat, bekommt keinen Autoplay.
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  sliders.forEach((el) => {
    const options = JSON.parse(el.dataset.gallery);
    const root = el.parentElement;

    new Swiper(el, {
      modules: [Navigation, Pagination, Autoplay, EffectFade, A11y, Keyboard],
      loop: true,
      speed: options.effect === 'fade' ? 1000 : 600,
      effect: options.effect,
      fadeEffect: { crossFade: true },
      autoplay: options.autoplay && !reducedMotion
        ? { delay: options.autoplay, disableOnInteraction: true, pauseOnMouseEnter: true }
        : false,
      keyboard: { enabled: true, onlyInViewport: true },
      navigation: {
        prevEl: root.querySelector('[data-gallery-prev]'),
        nextEl: root.querySelector('[data-gallery-next]'),
      },
      pagination: {
        el: root.querySelector('[data-gallery-pagination]'),
        clickable: true,
        bulletClass: 'gallery-bullet',
        bulletActiveClass: 'is-active',
      },
      a11y: {
        prevSlideMessage: 'Vorheriges Bild',
        nextSlideMessage: 'Nächstes Bild',
        paginationBulletMessage: 'Bild {{index}} anzeigen',
      },
    });
  });
}

const sliders = document.querySelectorAll('[data-gallery]');

if (sliders.length) {
  initSliders(sliders).catch((error) => console.error('Slider konnte nicht geladen werden:', error));
}
