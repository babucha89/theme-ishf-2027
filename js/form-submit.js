/*
 * Partnership & Newsletter forms: once a valid submit starts, the button is
 * disabled (no double submissions) and shows a "sending" label.
 * Text comes from data-sending on the button (translated server-side).
 */
(function () {
  'use strict';
  function lock(form) {
    var btn = form.querySelector('button[type="submit"]');
    if (!btn) { return; }
    btn.disabled = true;
    btn.setAttribute('aria-disabled', 'true');
    btn.setAttribute('aria-busy', 'true');
    var label = btn.getAttribute('data-sending');
    if (label) { btn.textContent = label; }
  }
  function unlock(form) {
    var btn = form.querySelector('button[type="submit"]');
    if (!btn || !btn.disabled) { return; }
    btn.disabled = false;
    btn.removeAttribute('aria-disabled');
    btn.removeAttribute('aria-busy');
    if (btn.hasAttribute('data-label')) { btn.innerHTML = btn.getAttribute('data-label'); }
  }
  document.addEventListener('DOMContentLoaded', function () {
    var forms = document.querySelectorAll('#form-partnership, #form-newsletter-landing');
    Array.prototype.forEach.call(forms, function (form) {
      var btn = form.querySelector('button[type="submit"]');
      if (btn) { btn.setAttribute('data-label', btn.innerHTML); }
      form.addEventListener('submit', function (e) {
        if (form.getAttribute('data-submitting') === '1') { e.preventDefault(); return; }
        form.setAttribute('data-submitting', '1');
        // Disable after the browser starts the submission (a disabled button is not sent).
        setTimeout(function () { lock(form); }, 0);
      });
    });
  });
  // Coming back with the browser's Back button must not leave the button locked.
  window.addEventListener('pageshow', function (e) {
    if (!e.persisted) { return; }
    Array.prototype.forEach.call(document.querySelectorAll('#form-partnership, #form-newsletter-landing'), function (form) {
      form.removeAttribute('data-submitting');
      unlock(form);
    });
  });
})();
