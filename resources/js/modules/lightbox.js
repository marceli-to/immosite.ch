// Full-screen lightbox for [data-lightbox] links (module:gallery, imported by gallery.js).
// One native <dialog> for the page: showModal() gives Esc, focus trap and inert background
// for free, and close() returns focus to the tile. The slides are clones of the tiles'
// <picture>, so avif/webp/mobile variants match without guessing file names here.

let dialog;
let swiper;
let group;

async function loadSwiper() {
  const [{ default: Swiper }, { Navigation, Keyboard, A11y }] = await Promise.all([
    import('swiper'),
    import('swiper/modules'),
    import('swiper/css'),
  ]);

  return { Swiper, modules: [Navigation, Keyboard, A11y] };
}

function createDialog() {
  const el = document.createElement('dialog');
  el.className = 'lightbox';
  el.setAttribute('aria-label', 'Bildergalerie');
  el.innerHTML = `
    <div class="swiper lightbox-swiper"><div class="swiper-wrapper"></div></div>
    <p class="lightbox-counter" aria-live="polite"></p>
    <button type="button" class="lightbox-button lightbox-close" aria-label="Schliessen">${icon('close')}</button>
    <button type="button" class="lightbox-button lightbox-prev" aria-label="Vorheriges Bild">${icon('prev')}</button>
    <button type="button" class="lightbox-button lightbox-next" aria-label="Nächstes Bild">${icon('next')}</button>`;

  el.querySelector('.lightbox-close').addEventListener('click', () => el.close());
  // A click on the dark area around the image closes; clicks on the image or buttons don't.
  el.addEventListener('click', (event) => {
    if (event.target === el || event.target.classList.contains('swiper-slide')) el.close();
  });
  el.addEventListener('close', () => document.documentElement.classList.remove('overflow-hidden'));

  document.body.append(el);

  return el;
}

function icon(name) {
  const paths = {
    close: 'M5 5l14 14M19 5L5 19',
    prev: 'M15 4l-8 8 8 8',
    next: 'M9 4l8 8-8 8',
  };

  return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="${paths[name]}"/></svg>`;
}

function slide(trigger) {
  const picture = trigger.querySelector('picture').cloneNode(true);
  const img = picture.querySelector('img');

  img.removeAttribute('class');
  img.removeAttribute('loading');
  picture.removeAttribute('class');

  const el = document.createElement('div');
  el.className = 'swiper-slide';
  el.append(picture);

  return el;
}

async function open(trigger) {
  const triggers = [...document.querySelectorAll(`[data-lightbox="${CSS.escape(trigger.dataset.lightbox)}"]`)];
  const index = triggers.indexOf(trigger);

  dialog ??= createDialog();
  const counter = dialog.querySelector('.lightbox-counter');

  if (group !== trigger.dataset.lightbox) {
    group = trigger.dataset.lightbox;
    swiper?.destroy(true, true);
    dialog.querySelector('.swiper-wrapper').replaceChildren(...triggers.map(slide));

    const { Swiper, modules } = await loadSwiper();
    const multiple = triggers.length > 1;

    dialog.classList.toggle('is-single', !multiple);
    swiper = new Swiper(dialog.querySelector('.lightbox-swiper'), {
      modules,
      initialSlide: index,
      speed: 400,
      keyboard: { enabled: true },
      navigation: { prevEl: dialog.querySelector('.lightbox-prev'), nextEl: dialog.querySelector('.lightbox-next') },
      a11y: { prevSlideMessage: 'Vorheriges Bild', nextSlideMessage: 'Nächstes Bild' },
      on: {
        slideChange: (s) => (counter.textContent = multiple ? `${s.activeIndex + 1} / ${triggers.length}` : ''),
      },
    });
    counter.textContent = multiple ? `${index + 1} / ${triggers.length}` : '';
  } else {
    swiper.slideTo(index, 0);
  }

  document.documentElement.classList.add('overflow-hidden');
  dialog.showModal();
  swiper.update();
}

document.addEventListener('click', (event) => {
  const trigger = event.target.closest('[data-lightbox]');

  // Modifier keys / middle click keep the link behaviour (open the JPG in a new tab).
  if (!trigger || event.metaKey || event.ctrlKey || event.shiftKey || event.button !== 0) return;

  event.preventDefault();
  open(trigger).catch((error) => {
    console.error('Lightbox could not be loaded:', error);
    window.location.href = trigger.href;
  });
});
