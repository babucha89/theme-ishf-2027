/* -----------------------------------------------------------------------------
   PAST EDITIONS (page-past-editions.php, includes/sections/past-editions.php)
   - Map of each edition (Mapbox GL JS), created the first time its modal opens:
     the library is only downloaded when a visitor opens a modal with a map.
   - Light / dark map style follows the site theme (html.dark-mode).
   - Deep links: /past-editions/#edition-dublin-2025 opens that modal; the hash
     follows the open modal so the address can be shared.
   Settings come from wp_localize_script: window.ishfEditions
----------------------------------------------------------------------------- */
(function () {
  'use strict';

  var cfg = window.ishfEditions || {};
  var maps = [];
  var loading = null;

  function isDark() {
    return document.documentElement.classList.contains('dark-mode');
  }

  // Load Mapbox GL JS + CSS once
  function loadMapbox() {
    if (window.mapboxgl) return Promise.resolve(window.mapboxgl);
    if (loading) return loading;
    loading = new Promise(function (resolve, reject) {
      var css = document.createElement('link');
      css.rel = 'stylesheet';
      css.href = cfg.mapboxCss;
      document.head.appendChild(css);

      var js = document.createElement('script');
      js.src = cfg.mapboxJs;
      js.async = true;
      js.onload = function () { resolve(window.mapboxgl); };
      js.onerror = function () { loading = null; reject(new Error('Mapbox GL JS could not be loaded')); };
      document.head.appendChild(js);
    });
    return loading;
  }

  function createMap(el) {
    if (el.dataset.ready || !cfg.token) return;
    el.dataset.ready = '1';
    var lng = parseFloat(el.dataset.lng), lat = parseFloat(el.dataset.lat);
    if (isNaN(lng) || isNaN(lat)) return;

    loadMapbox().then(function (mapboxgl) {
      mapboxgl.accessToken = cfg.token;
      var map = new mapboxgl.Map({
        container: el,
        style: isDark() ? cfg.styleDark : cfg.styleLight,
        center: [lng, lat],
        zoom: 14,
        cooperativeGestures: true, // page scroll is not hijacked: ctrl/two fingers to zoom
        attributionControl: true
      });
      map.addControl(new mapboxgl.NavigationControl({ showCompass: false }), 'top-right');
      var marker = new mapboxgl.Marker({ color: cfg.pinColor || '#CE4638' }).setLngLat([lng, lat]).addTo(map);
      if (el.dataset.label) marker.getElement().setAttribute('aria-label', el.dataset.label);
      map.getCanvas().setAttribute('aria-label', el.getAttribute('aria-label') || '');
      maps.push(map);
    }).catch(function () {
      el.hidden = true; // the "View on Google Maps" link stays
    });
  }

  // Switch map styles with the site theme
  new MutationObserver(function () {
    maps.forEach(function (map) { map.setStyle(isDark() ? cfg.styleDark : cfg.styleLight); });
  }).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

  function init() {
    if (!window.UIkit) return;
    var modals = document.querySelectorAll('.modal-ishf-edition');

    Array.prototype.forEach.call(modals, function (modal) {
      UIkit.util.on(modal, 'shown', function (e) {
        if (e.target !== modal) return;
        var el = modal.querySelector('.edition-map');
        if (el) {
          createMap(el);
          maps.forEach(function (map) { map.resize(); });
        }
        if (window.history && history.replaceState) history.replaceState(null, '', '#' + modal.id);
      });
      UIkit.util.on(modal, 'hidden', function (e) {
        if (e.target !== modal) return;
        if (window.history && history.replaceState && location.hash === '#' + modal.id) {
          history.replaceState(null, '', location.pathname + location.search);
        }
      });
    });

    // Deep link
    var hash = decodeURIComponent(location.hash.slice(1));
    if (hash) {
      var target = document.getElementById(hash);
      if (target && target.classList.contains('modal-ishf-edition')) UIkit.modal(target).show();
    }
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
