// Mapbox-Karte (module:map). Mapbox (~1.5 MB) wird nur geladen, wenn die Seite
// eine Karte hat; die Einstellungen kommen aus config/map.php via data-map.

const LOCALE = {
  'ScrollZoomBlocker.CtrlMessage': 'Zum Zoomen Strg + Scrollen verwenden',
  'ScrollZoomBlocker.CmdMessage': 'Zum Zoomen ⌘ + Scrollen verwenden',
  'TouchPanBlocker.Message': 'Karte mit zwei Fingern bewegen',
  'NavigationControl.ZoomIn': 'Vergrössern',
  'NavigationControl.ZoomOut': 'Verkleinern',
  'NavigationControl.ResetBearing': 'Nach Norden ausrichten',
};

const markerSvg = (color) =>
  `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40.8 52.8" width="41" height="53" aria-hidden="true"><path d="M40.8,20.4A20.4,20.4,0,0,0,0,20.4C0,31.7,20.4,52.8,20.4,52.8S40.8,31.7,40.8,20.4ZM10.9,20a9.5,9.5,0,1,1,9.5,9.5A9.6,9.6,0,0,1,10.9,20Z" fill="${color}"/></svg>`;

async function initMap(el) {
  const config = JSON.parse(el.dataset.map);

  if (!config.token) {
    return;
  }

  const [{ default: mapboxgl }] = await Promise.all([
    import('mapbox-gl'),
    import('mapbox-gl/dist/mapbox-gl.css'),
  ]);

  mapboxgl.accessToken = config.token;

  const map = new mapboxgl.Map({
    container: el,
    style: config.style,
    center: config.center,
    zoom: config.zoom,
    // Ein Finger scrollt die Seite, zwei Finger bewegen die Karte.
    cooperativeGestures: true,
    locale: LOCALE,
  });

  map.addControl(new mapboxgl.NavigationControl({ showCompass: false }), 'top-right');
  map.scrollZoom.disable();

  // Markerfarbe = Design-Token, damit ein Reskin die Karte mitnimmt.
  const color = getComputedStyle(document.documentElement).getPropertyValue('--color-primary').trim() || '#000';

  for (const marker of config.markers ?? []) {
    const node = document.createElement('div');
    node.innerHTML = markerSvg(color);
    node.title = marker.title ?? '';

    new mapboxgl.Marker({ element: node, anchor: 'bottom' })
      .setLngLat(marker.coordinates)
      .addTo(map);
  }

  const reveal = () => el.classList.remove('opacity-0');
  map.once('load', reveal);
  // 'load' kommt nie, wenn Style oder Kacheln scheitern: trotzdem einblenden
  // statt eine unsichtbare Flaeche stehen zu lassen.
  map.once('error', reveal);
}

document.querySelectorAll('[data-map]').forEach((el) => {
  initMap(el).catch((error) => console.error('Karte konnte nicht geladen werden:', error));
});
